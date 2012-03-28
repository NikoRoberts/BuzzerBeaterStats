<?php
class maxChart {
   var $data;         // The data array to display
   var $type = 1;     // Vertical:1 or Horizontal:0 chart
   var $title;        // The title of the chart
   var $width = 300;  // The chart box width 
   var $height = 200; // The chart box height
   var $metaSpaceHorizontal = 60; // Total space needed for chart title + bar title + bar value
   var $metaSpaceVertical = 60; // Total space needed for chart title + bar title + bar value
   var $variousColors = false;
   
   function maxChart($data){
      $this->data = $data;
   }
   
   function displayChart($title='', $type, $width=300, $height=200, $variousColor=false){
      $this->type   = $type;
      $this->title  = $title;
      $this->width  = $width;
      $this->height = $height;
      $this->variousColors = $variousColor;

		
		$mysqli = new mysqli('127.0.0.1','bbstatso_other','judgement','bbstatso_database');
                $mysqli->set_charset("utf8");
		$count = 0;

		$query="SELECT Count(*) AS count FROM user_tracking";
		$items = $mysqli->query($query) or die("Visit Count Failed!");
		while($return = mysqli_fetch_array($items))
		{
			$count = $return['count'];
		}
                $mysqli->close();

      echo '<div class="chartbox1" >
                <center><h2>'.$this->title.'</h2>
<i>'.$count.' users since 15-Feb-2009</i></center>'."\r\n";

        
      if ($this->type == 1)  $this->drawVertical();
      else $this->drawHorizontal();   
    
      echo '    </div>';

   }
   
   function getMaxDataValue(){
      $max = 0;
      
      foreach ($this->data as $key=>$value) {
         if ($value > $max) $max = $value;	
      }
      
      return $max;
   }
   
   function getElementNumber(){
      return sizeof($this->data);
   }
   
   function drawVertical(){
      $multi = ($this->height -$this->metaSpaceHorizontal) / $this->getMaxDataValue();
      $max   = $multi * $this->getMaxDataValue();
      $barw  = floor($this->width / $this->getElementNumber()) - 5;
      
      $i = 1;
      
      foreach ($this->data as $key=>$value) {
         $b = floor($max - ($value*$multi));
         $a = $max - $b;
         
         if ($this->variousColors) $color = ($i % 5) + 1;
         else $color = 1;
         $i++;
         
         echo '  <div class="barv">'."\r\n";
         echo '    <div class="barvvalue" style="margin-top:'.$b.'px; width:'.$barw.'px;">'.$value.'</div>'."\r\n";
	     echo '    <div><img src="charts/style/images/bar'.$color.'.png" style="width:'.$barw.'px; height:'.$a.'px;" /></div>'."\r\n";
         echo '    <div class="barvvalue" style="width:'.$barw.'px;">'.$key.'</div>'."\r\n";
         echo '  </div>'."\r\n";

      }
      
   }
   
   function drawHorizontal(){
      $multi = ($this->width-170) / $this->getMaxDataValue();
      $max   = $multi * $this->getMaxDataValue();
      $barh  = floor(($this->height - 35) / $this->getElementNumber());
      
      $i = 1;
      
	  mysql_pconnect("127.0.0.1","bbstatso_niko","judgement") or die("<center>SITE ERROR (DBC.1):<br>Admins are looking into it.</center>");
	  mysql_select_db("bbstatso_database") or die("<center>Database ERROR (DBC.2): Unable to select database...</center>");
		
      foreach ($this->data as $key=>$value) {
         $b = floor($value*$multi);

         if ($this->variousColors) $color = ($i % 5) + 1;
         else $color = 1;
         $i++;
//		 title substr($key,61,2);

		
		
		$id = 0;
                $mysqli = new mysqli('127.0.0.1','bbstatso_other','judgement','bbstatso_database');
                $mysqli->set_charset("utf8");
		$query="SELECT id, name FROM countries WHERE code='".$key."'";
		$items = $mysqli->query($query) or die("Visit Check Failed!");
		while($return = mysqli_fetch_array($items))
		{
			$id = $return['id'];
			$name = $return['name'];
		}
                $mysqli->close();

		if ($id!=0) $country = '<img title="'.$name.'" src="http://old.buzzerbeater.com/BBWeb/images/flags/flag_'.$id.'.gif">';
		else $country = $key;

         echo '  <div class="barh" style="height:20px;">'."\r\n";
         echo '    <div class="barhcaption" style="line-height:'.$barh.'px; width:90px;">'.$country.'</div>'."\r\n";
         echo '    <div style="margin-top: 4.5px" class="barhimage"><div style="background-color:#80FF00;width:'.$b.'px;height:11px;"></div></div>'."\r\n";
         echo '    <div class="barhvalue" style="line-height:'.$barh.'px; width:30px;">'.number_format($value,0).'</div>'."\r\n";
         echo '  </div>';

      }
      
   }
   
   
}


?>