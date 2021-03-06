<?php
require('../fpdf/fpdf.php');
class TPDF extends FPDF {
  public $B;
  public $I;
  public $U;
  public $HREF;
  public $angle=0;

  function PDF($orientation='P',$unit='mm',$format='A4'){
      //Appel au constructeur parent
      $this->FPDF($orientation,$unit,$format);
      //Initialisation
      $this->B=0;
      $this->I=0;
      $this->U=0;
      $this->
      $this->HREF='';
  }

  function PutLink($URL,$txt){
      //Place un hyperlien
      $this->SetTextColor(0,0,255);
      $this->SetStyle('U',true);
      $this->Write(3,$txt,$URL);
      $this->SetStyle('U',false);
      $this->SetTextColor(0);
  }

  function SetStyle($tag,$enable){
      //Modifie le style et sélectionne la police correspondante
      $this->$tag+=($enable ? 1 : -1);
      $style='';
      $this->SetFont('times','',11);
      foreach(array('7','8','9','10','11','12','13','14','24') as $p)
          if($this->$p>0)
              $police.=$p;
      foreach(array('B','I','U') as $s)
          if($this->$s>0)
              $style.=$s;
      $this->SetFont('',$style,$police);

  }

function WordWrap(&$text, $maxwidth){
    $text = trim($text);
    if ($text==='')
        return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;
    foreach ($lines as $line){
        $words = preg_split('/ +/', $line);
        $width = 0;
        foreach ($words as $word){
            $wordwidth = $this->GetStringWidth($word);
            if ($wordwidth > $maxwidth){
                // Word is too long, we cut it
                for($i=0; $i<strlen($word); $i++){
                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                    if($width + $wordwidth <= $maxwidth){
                        $width += $wordwidth;
                        $text .= substr($word, $i, 1);
                    }else{
                        $width = $wordwidth;
                        $text = rtrim($text)."\n".substr($word, $i, 1);
                        $count++;
                    }
                }
            }
            elseif($width + $wordwidth <= $maxwidth){
                $width += $wordwidth + $space;
                $text .= $word.' ';
            }else{
                $width = $wordwidth + $space;
                $text = rtrim($text)."\n".$word.' ';
                $count++;
            }
        }
        $text = rtrim($text)."\n";
        $count++;
    }
    $text = rtrim($text);
    return $count;
}

function RetourLigne($pos,$lg,$ht,$text,$bord,$ret,$ali,$fd){
	$x1=$this->GetX();
	if ($this->WordWrap($text,$lg) > 1){
		if ($bord=="1"){$bord1="LTR";$bord2="LR";$bord3="LBR";}else{$bord1="0";$bord2="0";$bord3="0";}
		$tts=explode("\n",$text);
		$this->Cell($lg,$ht,$tts[0],$bord1,1,$ali,$fd);
		$x2=$this->GetX();
		$lg1=(($x1-$x2)-$pos)+$lg;
		for ($i=1;$i < strlen($tts);$i++){
			$text1 .= " ".$tts[$i];
		}
		$text=ltrim($text1);
		//$this->Cell(190,6,$text,0,1,"L",0);
		$nb1=$this->WordWrap($text,$lg1);
		$tts1=explode("\n",$text);
		for ($o=0;$o < strlen($tts1);$o++){
			$this->SetX($pos+$x2);
			if ($tts1[$o]!=""){$this->Cell($lg1,$ht,$tts1[$o],$bord2,1,$ali,$fd);}
		}
		$this->Cell($lg1,$ht,$tts1[$nb1],$bord3,$ret,$ali,$fd);
	}else{
		$this->Cell($lg,$ht,$text,$bord,$ret,$ali,$fd);
	}
}
function Rotate($angle,$x=-1,$y=-1){
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0){
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}
function _endpage(){
    if($this->angle!=0)    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}
function RotatedText($x,$y,$txt,$angle){
    //Rotation du texte autour de son origine
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}
function RotatedImage($file,$x,$y,$w,$h,$angle){
    //Rotation de l'image autour du coin supérieur gauche
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}
function RotatedCell($angle,$x,$y,$w,$h,$txt,$bor,$ln,$aling){
    //Rotation de la celulle autour du coin supérieur gauche
    $this->Rotate($angle,$x,$y);
	$this->Cell($w,$h,$txt,$bor,$ln,$aling); 
    $this->Rotate(0);
}
}
?>