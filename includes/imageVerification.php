<?php
/**
 * ImageVerification class
 */
 
class imageVerification
{
    /**
		 * @var array fonts to be used in constructing the code
		 */
		var $fonts = array();

		/**
		 * @var integer width of image generated
		 */
		var $img_width = 150;
		
		/**
		 * @var integer height of image generated
		 */
		var $img_height = 50;
		
		/**
		 * @var integer minimum angle of rotation for letters
		 */
		var $minAngle = -30;

		/**
		 * @var integer maximum angle of rotation for letters
		 */
		var $maxAngle = 30;
		
		/**
		 * @var string user input code
		 */
		var $userCode;
		
		/**
		 * @var string system generated code
		 */
		var $systemCode;
		
		/**
		 * @var boolean whether or not to check case-sensitivity
		 */
		var $caseSensitive = true;
    
		/**
		 * constructor
		 */
	  function imageVerification()
		{
		    session_start();
				$this->getCode();
				if (stristr($_SERVER["HTTP_HOST"], 'localhost')) {
				    $this->fonts = array('arial.ttf','ariblk.ttf','ariali.ttf','times.ttf','timesi.ttf','timesbd.ttf','verdana.ttf','verdanai.ttf','verdanab.ttf');
				} else {
				    $this->fonts = array(
                "/usr/share/fonts/java/LucidaTypewriterBoldOblique.ttf",
                "/usr/share/fonts/java/LucidaBrightDemiBold.ttf",
                "/usr/share/fonts/java/LucidaBrightDemiItalic.ttf",
                "/usr/share/fonts/java/LucidaBrightItalic.ttf",
                "/usr/share/fonts/java/LucidaBrightRegular.ttf",
                "/usr/share/fonts/java/LucidaSansDemiBold.ttf",
                "/usr/share/fonts/java/LucidaSansDemiOblique.ttf",
                "/usr/share/fonts/java/LucidaSansOblique.ttf",
                "/usr/share/fonts/java/LucidaSansRegular.ttf",
                "/usr/share/fonts/java/LucidaTypewriterBold.ttf",
                "/usr/share/fonts/java/LucidaTypewriterOblique.ttf",
                "/usr/share/fonts/java/LucidaTypewriterRegular.ttf"
						);
				}
		}
	
	  /**
		 * getCode
		 * @param integer number of letters/numbers to generate
		 */
	  function getCode($letters = 6)
		{
		    if (!isset($_SESSION["verificationCode"])) {
				    $string = array();
		        for ($i = 1; $i <= $letters; $i++) {
    				    $type = rand(1,3);
    		        switch($type)
    						{
    				    case '1':
    						    /* upper case */
    					      $min = 65;
    					      $max = 90;
    				        break;
    				    case '2':
    					      /* lower case */
    					      $min = 97;
    					      $max = 122;
    				        break;
    				    case '3':
    					      /* numbers */
          					$min = 48;
    			      		$max = 57;
    				        break;
    			      }
    			      $string[] = chr(rand($min,$max));
		        }
    		    shuffle($string);
				    $this->systemCode = implode('', $string);
    		    $_SESSION["verificationCode"] = $this->systemCode;
				} else {
				    $this->systemCode = $_SESSION["verificationCode"];
				}
	  }
	
	  /**
		 * getImage
		 * outputs an image with the code hidden in it
		 */
  	function getImage()
		{
				header("Content-type: image/jpeg");
		    $this->img = imagecreatetruecolor($this->img_width, $this->img_height);
    		$bg = imagecolorallocate($this->img, 235, 235, 235);
		    $white = imagecolorallocate($this->img, 255, 255, 255);
		    $black = imagecolorallocate($this->img, 51, 51, 51);
		    $colours = array();
				$colours[] = imagecolorallocatealpha($this->img, 204, 204, 204, 75);
		    $colours[] = imagecolorallocatealpha($this->img, 153, 153, 153, 75);
		    $colours[] = imagecolorallocatealpha($this->img, 102, 102, 102, 75);
				/* fill in background */
    		imagefilledrectangle($this->img, 0, 0, $this->img_width, $this->img_height, $bg);
				foreach ($colours as $colour) {
		        /* add some ellipses */
		        imagefilledellipse($this->img, ceil(rand(5, $this->img_width)), ceil(rand(5, $this->img_height)), ceil(rand(5, $this->img_height)), ceil(rand(5, $this->img_height)), $colour);
		        imagefilledellipse($this->img, ceil(rand(5, $this->img_width)), ceil(rand(5, $this->img_height)), ceil(rand(5, $this->img_height)), ceil(rand(5, $this->img_height)), $colour);
		        /* add some lines */
		        imageline($this->img,rand(1, $this->img_width), rand(1, $this->img_height), rand(ceil($this->img_width / 2), $this->img_width), rand(ceil($this->img_height / 2) ,$this->img_height), $colour);
		        imageline($this->img,rand(1, $this->img_width), rand(1, $this->img_height), rand(ceil($this->img_width / 2), $this->img_width), rand(ceil($this->img_height / 2) ,$this->img_height), $colour);
		        imageline($this->img,rand(1, $this->img_width), rand(1, $this->img_height), rand(ceil($this->img_width / 2), $this->img_width), rand(ceil($this->img_height / 2) ,$this->img_height), $colour);
		        imageline($this->img,rand(1, $this->img_width), rand(1, $this->img_height), rand(ceil($this->img_width / 2), $this->img_width), rand(ceil($this->img_height / 2) ,$this->img_height), $colour);
		        imageline($this->img,rand(1, $this->img_width), rand(1, $this->img_height), rand(ceil($this->img_width / 2), $this->img_width), rand(ceil($this->img_height / 2) ,$this->img_height), $colour);
		        imageline($this->img,rand(1, $this->img_width), rand(1, $this->img_height), rand(ceil($this->img_width / 2), $this->img_width), rand(ceil($this->img_height / 2) ,$this->img_height), $colour);
				}
		    /* add some filters */
				//@imagefilter($this->img, IMG_FILTER_GAUSSIAN_BLUR);
				/* add a black border */
		    imagefilledrectangle($this->img, 0, 0, $this->img_width, 0, $black);
		    imagefilledrectangle($this->img, $this->img_width - 1, 0, $this->img_width - 1, $this->img_height - 1, $black);
		    imagefilledrectangle($this->img, 0, 0, 0, $this->img_height - 1, $black);
		    imagefilledrectangle($this->img, 0, $this->img_height - 1, $this->img_width, $this->img_height - 1, $black);
		    /* add the code */
		    for ($i = 0; $i < strlen($this->systemCode); $i++) {
			      if ($i == 0) {
				        $y = 10;
			      } else {
        				$y += 23;
			      }
      			$angle = rand($this->minAngle, $this->maxAngle);
			      $font = rand(0, sizeof($this->fonts) - 1);
      			$size = rand(ceil($this->img_height / 4), ceil($this->img_height / 2));
      			imagettftext($this->img, $size, $angle, $y, 35, $black, $this->fonts[$font], $this->systemCode{$i});
		    }
		    /* output the image */
		    imagejpeg($this->img);
	  }
	
	  /**
		 * getInput
		 * @param string extra attributes and values for input control
		 * @return string HTML input field for a form
		 */
	  function getInput($extra = '')
		{
		    return "<input type=\"text\" name=\"verificationCode\" id=\"verificationCode\" " . $extra . " />";
	  }
		
		/**
		 * verifyCode
		 */
    function verifyCode($code)
		{	
		    $this->userCode = $code;
		    $this->systemCode = $_SESSION['verificationCode'];
		    if ($this->caseSensitive) {
				    return (strcmp($this->userCode, $this->systemCode) == 0);
        } else {
				    return (strcasecmp($this->userCode, $this->systemCode) == 0);
				}
		}
		
		/**
		 * clear
		 * clears the current code and gets a new one
		 */
		function clear()
		{
		    unset($_SESSION["verificationCode"]);
				$this->getCode();
		}
}
?>