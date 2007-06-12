#!/usr/bin/perl
#
die "Usage : trtiff2png<répertoire source> <répertoire destination> <echelle 1>...<echelle n>\n" if ($ARGV >= 2);

$dirbase=@ARGV[0];
$dirdest=@ARGV[1];
$n=2;
while (@ARGV[$n]){
	$echelle=@ARGV[$n];
	mkdir "$dirbase/$dirdest$echelle";
	opendir(IN,$dirbase) || die "$dirbase n'est pas un répertoire valide\n";
	@liste = readdir(IN);
	foreach (@liste){
		$fic=$_;
		split(/\./);
		#$fic2= @_[0].".png";
		#if (@_[1] eq "tif"){ 
		#	$ch=$echelle."% ".$echelle."% ".$dirbase."/".$fic." ".$dirbase."/".$dirdest.$echelle."/".$fic2;
		#	print $ch;
		#	exec "/usr/local/bin/gdal_translate -ot Byte -of PNG -outsize $ch"; 
		#}
		if (@_[1] eq "tfw" ){
			open(tfw, $dirbase."/".$fic);
				$lg=0;
				$text="";
				while(<tfw>){
					if ($lg==0){
						$pp=$_;
						$p1=(100/$echelle)*$pp;
						$text.=$p1."\n";
					}
					if ($lg==1){$text.=($_*1)."\n";}
					if ($lg==2){$text.=($_*1)."\n";}
					if ($lg==3){
						$pp2=$_;
						$p2=(100/$echelle)*$pp2;
						$text.=$p2."\n";
					}
					if ($lg==4){
						$text.=($_ - ($pp / 2)) + ($p1 / 2)."\n";
					}
					if ($lg==5){
						$text.=($_ - ($pp2 / 2)) + ($p2 / 2)."\n";
					}
					$lg++;
				}
			open( $fsql, ">>$dirbase/$dirdest$echelle/$fic");
			print $fsql $text;
			close($fsql);
			close(tfw);
		}
	}
	$ch="/home/sig/capm_ortho_foto/transformetiff2png.sh $dirbase $dirdest $echelle $echelle";
	print $ch;
	exec $ch;
	$n++;
}