<?php
/**
 * Fake Threads In PHP
 * @author Janis Elsts (W-Shadow)
 * @link http://w-shadow.com/
 *
 * Requires PHP 5
 *
 * These classes simulate threading by asynchronous HTTP POST requests. This way they will
 * work even on systems where pcntl_fork() is not available (e.g. Windows systems). The Thread class 
 * starts a "thread" by sending a specially crafter POST request to $_SERVER["SCRIPT_NAME"]. The request
 * is received by handler code in this file, and the PHP function specified in the request is executed.
 * The handler serializes and outputs the function's return value. This output is then decoded by the Thread
 * class and stored in Thread::result.
 *
 * Inspired by the Thread class by Alex Lau. 
 */

/**
 * Thread class represents a single fake thread. 
 */
class Thread
{
	var $func;
	var $arg;
	var $thisFileName; 
	var $fp;
	var $host;
	var $port;
	var $finished = false;
	var $response = ''; //the raw HTTP response from the thread
	var $result = null; //decoded response (after thread is done)
	
	/**
	 * Constructor
	 */
	function Thread($host,$port='')
	{
		$this->host = $host;
		if (!empty($port)){
			$this->port = $port;
		}else{
			$this->port = 80;
		}
		$this->thisFileName = $_SERVER["SCRIPT_NAME"];
	}
	
	/**
	 * Set the PHP function that this thread will execute
	 *
	 * $func - the name of the function
	 * $arg - an array of function arguments 
	 */
	function setFunc($func,$arg=null)
	{
		$i=0;
		$this->arg = array();
		if (isset($arg))
		{
			$this->arg = $arg;
		}
		$this->func = $func;
	}
	
  /**
   * Start the fake thread. Send the initial HTTP request.
   */
	function start($connection_timeout = 30)
	{
		$this->finished = false;

		//Start out with a blocking socket.
		$this->fp = fsockopen($this->host,$this->port, $errno, $errstr, $connection_timeout);
		if (!$this->fp)
		{
			$this->finished = true;
			return false;
		}

		//Build a HTTP POST query. POST is more suitable than GET here because 
		//we can pass more parameter data in POST.
		$params = http_build_query(array(
				'threadrun' => 1,
				'f' => $this->func,
				'a' => $this->arg
			), '', '&');
		$header = "POST ".$this->thisFileName." HTTP/1.1\r\n";
		$header .= "Host: ".$this->host."\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: ".strlen($params)."\r\n";
		$header .= "Connection: Close\r\n\r\n";
		$header .= $params;

		fputs($this->fp,$header);
		stream_set_blocking($this->fp, 0);
		return true;
	}
	
	/** 
	 * Check if thread is running and process thread's output, if any is present. 
	 * Returns TRUE if the thread is still executing, FALSE if it's finished.
	 *
	 * $tv_sec - seconds to wait for data from thread.
	 * $tv_usec - microseconds to wait for data from thread.
	 */
	function query($tv_sec=0, $tv_usec=100000)
	{
		if ($this->finished) return false;
		
		$streams = array($this->fp);
		$dummy1 = null; 
		$dummy2 = null;
		if (false === ($num_changed_streams = stream_select($streams, $dummy1, $dummy2, $tv_sec, $tv_usec)))
		{
		    /* Error handling, or it would be if there was any useful code here. */
		    $this->finished = true;
		} elseif ($num_changed_streams > 0)
		{
		    /* At least on one of the streams something interesting happened.
		       There's only one stream that it could be, so use that.
			*/
			$buffer = '';
			while (!feof($this->fp))
			{
				$buffer = fread($this->fp, 8192);
				if (strlen($buffer)==0)
				{
					//Reached the end of available data
					break;
				}
				$this->response .= $buffer;
			}
			if (feof($this->fp))
			{
				$this->finished = true;
				//Decode and store the result
				if (preg_match('/\[RESPONSE\](.+?)\[\/RESPONSE\]/i', $this->response, $matches))
				{
					//Response found. Now unserialize it.
					$this->result = unserialize(trim($matches[1]));
				} else
				{
					//Didn't find the response code
					$this->result = null;
					echo "Decoding error! The response was : \n";
					print_r($this->response);
					echo "\n";
				}
				fclose($this->fp);
			}
		}
		return !$this->finished;
	}
	
	/**
	 * Wait until the thread exits (possibly indefinitely if the thread doesn't ever quit)
	 */
	function wait_for_completion()
	{
		//run until finished
		while($this->query(null));
		return $this->finished;
	}
}

/**
 * ThreadManager - an simple utility class for easier management of multiple threads.
 */
class ThreadManager
{
	var $active_threads; //array of currently running threads
	var $finished_threads; //array of threads that have finished execution
	var $last_auto_idnum = 0; //the numeric component of auto-generated indexes
	
	/**
	 * Class constructor
	 */
	function ThreadManager()
	{
		$this->active_threads = array();
		$this->finished_threads = array();
	}
	
	/**
	 * Add an existing thread to the manager queue.
	 * 
	 * $thread - and existing thread that has already been started.
	 * $id - (optional) an unique ID. Will be assigned automatically if not specified. 
	 */
	function register_thread($thread, &$id=null)
	{
		if (!isset($id))
		{
			//If ID is not provided, automatically generate one
			$this->last_auto_idnum++;
			$id = '_thread_'.$this->last_auto_idnum;
		}
		if (!$thread->finished)
		{
			$this->active_threads[$id] = $thread;
		} else
		{
			$this->finished_threads[$id] = $thread;
		}
		return $id;
	}
	
	/**
	 * Remove a thread from the manager queues.
	 *
	 * $id - the thread's unique ID
	 */
	function remove_thread($id)
	{
		if (isset($this->finished_threads[$id]))
		{
			unset($this->finished_threads[$id]);
		}
		if (isset($this->active_threads[$id]))
		{
			unset($this->active_threads[$id]);
		}
	}
	
	/**
	 * Process all threads. Returns the number of threads that are still running.
	 *
	 * $sec_tv - how many seconds to wait (each thread).
	 * $usec_tv - how many microseconds to wait (each thread).
	 */
	function query($sec_tv=0, $usec_tv=200000)
	{
		$threads_running = 0;
		//Run every thread that hasn't finished executing
		foreach($this->active_threads as $id => $thread)
		{
			if (!$thread->query($sec_tv, $usec_tv))
			{
				//Thread execution finished.
				$this->finished_threads[$id] = $thread;
				unset($this->active_threads[$id]); //Hmm.
			} else
			{
				$threads_running++;
			}
		}
		return $threads_running;
	}
	
	/**
	 * Process all threads. Returns the number of threads that are still running.
	 * Like query(), except the timeout is split equally over all threads.
	 *
	 * $sec_tv - how many seconds to wait (in total).
	 * $usec_tv - how many microseconds to wait (in total).
	 */
	function query_e($sec_tv=0, $usec_tv=200000)
	{
		$threads_running = 0;
		if (count($this->active_threads)<1) return 0;
		//Calculate equal timeouts for every thread
		$sec_tv = $sec_tv / count($this->active_threads);
		$usec_tv = $usec_tv / count($this->active_threads);
		//Run every thread that hasn't finished executing
		foreach($this->active_threads as $id => $thread)
		{
			if (!$thread->query($sec_tv, $usec_tv))
			{
				//Thread execution finished.
				$this->finished_threads[$id] = $thread;
				unset($this->active_threads[$id]); //Hmm.
			} else
			{
				$threads_running++;
			}
		}
		return $threads_running;
	}
	
	/**
	 * Create and start a new thread. Returns the ID assigned to the thread or FALSE on error.  
	 *
	 * $func - the thread function name.
	 * $arguments - array of function parameters, if any.
	 * $id - (optional) the unique ID for the thread. Will be auto-assigned if omitted. 
	 */
	function create_thread($func, $arguments=null, $id=null, $host='www.buzzerbeaterstats.com', $port=80)
	{
		$thread = new Thread($host, $port);
		$thread->setFunc($func, $arguments);
		if ( $thread->start() )
		{
			$this->register_thread($thread, $id);
		} else
		{
			return false;
		}
		return $id;
	}
	
	/**
	 * Remove a finished thread from the internal queue and return it. Returns FALSE if there are no 
	 * threads that have completed execution.
	 */
	function pop_finished_thread()
	{
		if (count($this->finished_threads)<1)
			return false;
		//Fetch a key-value pair from the array
		$item = each($this->finished_threads);
		//Remove the thread from internal lists
		$this->remove_thread($item['key']);
		//Return the finished thread
		return $item['value'];
	}
}

/**
 * This is the code that starts the thread function and encodes it's output 
 * when the Thread class starts a new instance of this script.
 */
if (isset($_POST['threadrun']))
{
	$arg_str = "";
	if (isset($_POST['a']))
	{
		foreach ($_POST['a'] as $argument)
		{
			if ($arg_str) $arg_str .= ", ";
			$arg_str .= var_export($argument, true); 
		}
	}
	$code = "\$return = ".$_POST['f']."(".$arg_str.");";
	eval($code);
	
	/* 
	The result value is wrapped in these pseudo-tags so that it will be possible to find
	and decode it even if the thread produces some other output (e.g. error messages). 
	*/
	die("[RESPONSE]".serialize($return)."[/RESPONSE]");
}

?> 