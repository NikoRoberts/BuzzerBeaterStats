Max's Chart

Max's Chart is a professional but very easy to use PHP based chart generator. You can create beautiful horizontal or vertical charts 
fast and easy. You don't need any databse or an image library to generate a full CSS driven output. No tables used at all. 
You can extend the style by editing a simple CSS file. An example code is also included.

Usage:
1. Upload the files to your webserver.
2. Include maxChart.class.php into your script.
3. Create an associative array with data you want to display.
4. Create a new maxChart object with your data.
5. Call the displayChart() function.

Small example:
    <?php
        require_once('maxChart.class.php');
        $data3['Windows'] = 55;
        $data3['Linux'] = 7;
        $data3['Mac'] = 3;
        $mc3 = new maxChart($data3);
        $mc3->displayChart('Demo chart - 4',1,200,150,true);
    ?>    

The attached index.php file is a complete example how to use the class.
