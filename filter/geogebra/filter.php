<?php

function geogebra_filter($courseid, $text) {
   global $CFG;

   //$pattern="(\?width\=[0-9]+)(xheight=[0-9]+)";
   //$patternpregrepl="/\?width\=[0-9]+xheight=[0-9]+/";
   $pattern="(width\=[0-9]+)(height=[0-9]+)";
   $patternpregrepl="/width\=[0-9]+height=[0-9]+/";

   $widthPattern=   "/width\=\"[0-9]+\"/";
   $heightPattern= "/height\=\"[0-9]+\"/";

   //$pattern="/\?width\=[0-9]+)(&height=[0-9]+)";

   $search = array(
                '/<a(.*?)href=\"([^<]+)\.ggb\?d=([\d]{1,3}%?)x([\d]{1,3}%?)\"([^>]*)>(.*?)<\/a>/is',
                '/<a(.*?)href=\"([^<]+)\.ggb\"([^>]*)>(.*?)<\/a>/is'
             );

   $replace = array();

   $replace[0] = ' <applet codebase="./" height="299"';
   $replace[0] .= ' archive="';
   $replace[0] .=  $CFG->wwwroot.'/filter/geogebra/geogebra.jar"';
   $replace[0] .=  ' width="299" code="geogebra.GeoGebraApplet">';
   $replace[0] .= ' <param value="\\2.ggb" name="filename" /><param value="false" name="framePossible" /> </applet> ';


   $replace[1] = ' <applet codebase="./" height="299"';
   $replace[1] .= 'archive="';

   $replace[1] .= $CFG->wwwroot.'/filter/geogebra/geogebra.jar"';
   $replace[1] .= ' width="389" code="geogebra.GeoGebraApplet">';
   $replace[1] .= '<param value="\\2.ggb" name="filename" /><param value="false" name="framePossible" /></applet> ';


   $match = ereg($pattern,$text,$regs);
   if ($match ==true) {
      $w=$regs[1]; $h=$regs[2];
      $neu = preg_replace($patternpregrepl,"",$text);
   }
   else {
      $w=400;$h=400;$neu=$text;
   }

   // $neu = preg_replace($patternpregrepl,"",$text);

    $text = preg_replace($search, $replace, $neu);


   if ($match ==true){
      $wr= '"'.$w.'"';
      $hr= '"'.$h.'"';
      //zerlegen:
      $wr1=substr($w,0,6);$wr2=substr($w,6);
      $hr1=substr($h,0,7);$hr2=substr($h,7);
      $wre= $wr1.'"'.$wr2.'"';
      $hre= $hr1.'"'.$hr2.'"';

      $text = preg_replace($widthPattern,$wre,  $text);
      $text = preg_replace($heightPattern,$hre , $text);
   }
   return $text;
}
?>

