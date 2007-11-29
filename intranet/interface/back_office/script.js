/*Copyright Ville de Meaux 2004-2007
contributeur: jean-luc Dechamp - robert Leguay 
sig@meaux.fr

Ce logiciel est un programme informatique fournissant une interface cartographique WEB communale. 

Ce logiciel est régi par la licence CeCILL-C soumise au droit français et
respectant les principes de diffusion des logiciels libres. Vous pouvez
utiliser, modifier et/ou redistribuer ce programme sous les conditions
de la licence CeCILL-C telle que diffusée par le CEA, le CNRS et l'INRIA 
sur le site "http://www.cecill.info".

En contrepartie de l'accessibilité au code source et des droits de copie,
de modification et de redistribution accordés par cette licence, il n'est
offert aux utilisateurs qu'une garantie limitée.  Pour les mêmes raisons,
seule une responsabilité restreinte pèse sur l'auteur du programme,  le
titulaire des droits patrimoniaux et les concédants successifs.

A cet égard  l'attention de l'utilisateur est attirée sur les risques
associés au chargement,  à l'utilisation,  à la modification et/ou au
développement et à la reproduction du logiciel par l'utilisateur étant 
donné sa spécificité de logiciel libre, qui peut le rendre complexe à 
manipuler et qui le réserve donc à des développeurs et des professionnels
avertis possédant  des connaissances  informatiques approfondies.  Les
utilisateurs sont donc invités à charger  et  tester  l'adéquation  du
logiciel à leurs besoins dans des conditions permettant d'assurer la
sécurité de leurs systèmes et ou de leurs données et, plus généralement, 
à l'utiliser et l'exploiter dans les mêmes conditions de sécurité. 

Le fait que vous puissiez accéder à cet en-tête signifie que vous avez 
pris connaissance de la licence CeCILL-C, et que vous en avez accepté les 
termes.*/
var largeurbox=800,hauteurbox=600,larmap=620,haumap=520,ratiovue=larmap/haumap,imp='',coucherasterold='',coucherasterposi='',Rpressed=0;
var zoomtool='',RevtX=0,RevtY=0,couraster=0,mapPosX=0,mapPosY=0,nbsplit=0,liste_scroll_decale=0,offsetXmap=0,offsetYmap=0,scroll_y=0;
var liste_scroll_appui=false,ratio=0,svgdoc='',overini=0,pressed=0,rectOveXcorner='',rectOveYcorner='',rectXcorner=0,rectYcorner=0;
var rect1Ovewidth=0,rect1Oveheight=0,rect1UlXCorner=0,rect1UlYCorner=0,evtX=0,evtY=0,coucherastervisible='',couchesvgvisible='';
var coucherasteraenvoye='',xnulcorner=0,ynulcorner=0,nWidth=0,nHeight=0,newViewport='',ini=0,coulfill='',opacityfill='',multi=0;
var selectio='',id1='',placid='',n_url='',tee='',tape_autorise=0,tapenum_autorise=0,zoomrecherche=100,svgRect='',Rect1='',lin1='';
var lin2='',svgMainViewport='',svgns='http://www.w3.org/2000/svg',ligne='',ligne1='',traitpret=0,lx=0,ly=0,lxdist=0,lydist=0;
var xm='',ym='',cotation=0,longtrait=0,angle=0,posix=0,posiy=0,countrait=0,countcotation=0,countsurface=0,selectid='boutonselect';
var selectstroke='0',parce="''",bouton_distance=0,cumul=0,distance=0,surface=0,polygon=0,polyg='',indice='',count=0,image='';
var nom='',noactive=0,retparam1='',Periode='',retparam2='',idparam1='',idparam2='',phrase='',phrase1='',valn=0,valconteur=new Array;
var varcotations=new Array,ini_use=0,coul_choisie=0,appui=false,cible='',comp_rouge=80,comp_vert=80,comp_bleu=80,opacite=1,type='';
var remplissage='',contour='',bascule_fond=0,bascule_contour=0,cible_txt='',ident_style='',tabl_style='',numero_control='',idthe='',geom_type="",appui_txt_legende='false',posi_txt_legende='';
var pro_over="",pro_click="",pro_out="",pro_vect="",pro_min_v="",pro_max_v="",pro_max_r="",pro_selec="",pro_princip="",pro_partiel="",pro_initial="",idtheme="",pro_ident="",pro_ad="",menu_derou="",pro_schema="",pro_tabl="",idappthe="",deroulinit="",mod_theme="",countvalfixe=0,countvalfourchette=0,ferme="",bouton_thematique="",col_geom,htmenu="";
function init(evt) 
	{
HTTP()
	svgdoc=evt.target.ownerDocument
	svgRect = svgdoc.getElementById('locationRect');
	Rect1 = svgdoc.getElementById('Rect1');
	lin1 = svgdoc.getElementById('lin1');
	lin2 = svgdoc.getElementById('lin2');
	svgMainViewport = svgdoc.getElementById('mapid');
	
Zoomto(evt,zoomVal);
deter_zoom=Math.round(2400/intervale);
zoomrecherche=zoommin+(intervale*deter_zoom);
}
function codalph(ch)
{
         if(ch>26 && 260>ch)
		 {
			ve=Math.floor(ch/26);
			txt=String.fromCharCode(96+ve);
            txt=txt+String.fromCharCode(65+(ch-(26*ve)));
         }
		 else
		 {
            txt=String.fromCharCode(64+ch);
         }
         if(ch==0)
		 {
			txt="";
		 }
         return txt
}
function derouler(evt,x,y)
{
var nlay=nblayer;	
var objet=svgdoc.getElementById("d" + x);
if (objet.getStyle().getPropertyValue("visibility") == "hidden")
	{
	objet.getStyle().setProperty('visibility','visible')
	svgdoc.getElementById("soustheme" + x).getStyle().setProperty('visibility','hidden')
	for(z=1;z<=y;z++)
	{
	svgdoc.getElementById("tra"+x+codalph(z)).getStyle().setProperty('visibility','hidden')	
	}
	for (i=1;i<=nblayer;i++)
		{
			if(svgdoc.getElementById("theme" + i).getAttribute("so")>0 && svgdoc.getElementById("d" + i).getStyle().getPropertyValue("visibility") == "hidden")
			{
			
			nlay=nlay+Math.round(svgdoc.getElementById("theme" + i).getAttribute("so"))
			}
			if(i==x+1)
			{
			svgdoc.getElementById("theme" + i).setAttributeNS(null,'transform','translate(0,0)');
			}
		}
	} 
	else
	{
	objet.getStyle().setProperty('visibility','hidden')
if (svgdoc.getElementById("tra" + x).getStyle().getPropertyValue("visibility") == "visible")
	{
for(z=1;z<=y;z++)
	{
	svgdoc.getElementById("tra"+x+codalph(z)).getStyle().setProperty('visibility','visible')	
	}
	}
for (i=1;i<=nblayer;i++)
		{
			if(svgdoc.getElementById("theme" + i).getAttribute("so")>0 && svgdoc.getElementById("d" + i).getStyle().getPropertyValue("visibility") == "hidden")
			{
			
			nlay=nlay+Math.round(svgdoc.getElementById("theme" + i).getAttribute("so"))
			}
			if(i==x+1)
			{
			svgdoc.getElementById("theme" + i).setAttributeNS(null,'transform','translate(0,'+Math.round(12*y)+')');
			}
	}
svgdoc.getElementById("soustheme" + x).getStyle().setProperty('visibility','visible')
}
if(17+(nlay*12)>291)
{
svgdoc.getElementById("scroll_cursor").setAttribute("height",291-((nlay-23)*12));
}
else
{
svgdoc.getElementById("layer").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("curseur").setAttributeNS(null,'transform','translate(0,0)')
svgdoc.getElementById("scroll_cursor").setAttribute("height",291);
}
}

function switchColor (evt,property,newcolor,idpath,idoption)
{
var target;
svgdoc = evt.getTarget().getOwnerDocument();
target = get_target(evt);
if(idoption!='')
{
	svgdoc.getElementById(idoption).style.setProperty (property, newcolor);
}
else if (idpath.substring(0,17) == 'composedLineStyle')
   {
	 objpath = svgdoc.getElementById(idpath.substring(17,idpath.length));objpath.setAttributeNS(null,'style','');
	 tempobj = svgdoc.getElementById(idpath);tempobj.style.setProperty(property,newcolor);
	 }
else
	{
	target = get_target(evt);
	target.style.setProperty (property, newcolor);
	}
}

function get_target (evt){
var target = evt.getTarget(); 
while (target && !target.getAttributeNS(null,'id'))target = target.getParentNode();
return target;
}
function determ_ratio()
{
if ((window.innerWidth/largeurbox) < (window.innerHeight/hauteurbox))
{
scaleFactor=largeurbox / window.innerWidth;
ratio=window.innerWidth / largeurbox;
}
else
{
scaleFactor=hauteurbox/window.innerHeight;
ratio=window.innerHeight/hauteurbox;		
}
offsetXmap = (window.innerWidth-(largeurbox/scaleFactor))/2+(mapPosX / scaleFactor);
offsetYmap = (window.innerHeight-(hauteurbox/scaleFactor))/2+(mapPosY / scaleFactor);	
}
function showinfotip (evt,info1){
splitString="";
var reg111=new RegExp("[$\n]+", "g");
splitString = info1.split(reg111);
nbsplit = splitString.length;
var scale = svgMainViewport.getCurrentScale();
var translateX = svgMainViewport.getCurrentTranslate().x;
var translateY = svgMainViewport.getCurrentTranslate().y;
var pixel = 1 / scale;
var infotip = 'infotip';
var infotiprect = 'infotipRect';
var svgobj = svgdoc.getElementById (infotip);
var svgobjtxt = svgobj;
svgobj.setAttributeNS(null,'x', (evt.getClientX()-offsetXmap)/ratio+9);
svgobj.setAttributeNS(null,'y', (evt.getClientY()-offsetYmap)/ratio);
var svgstyle = svgobj.style;
svgstyle.setProperty ('visibility', 'visible');
svgstyle.setProperty('font-size', 8);
svgobj1 = svgobj.getFirstChild();
if (nbsplit == 1)
	{
	svgobj1.setData(info1);
	}
	else
	{
	svgobj1.setData(splitString[0]);
	for (k=1; k<nbsplit ; k++)
		{
		myspan = svgdoc.createElement('tspan');
		myspan.setAttributeNS(null,'dy', 1.2+'em');
		myspan.setAttributeNS(null,'id', k);
		myspan.setAttributeNS(null,'x', (evt.getClientX()-offsetXmap)/ratio+9);
var reg1=new RegExp("<i>","g");
if (splitString[k].match(reg1)) {
 myspan.getStyle().setProperty('font-style','italic');
splitString[k]=splitString[k].replace(reg1,"");
}
var reg2=new RegExp("<rgb>","g");
if (splitString[k].match(reg2)) {
	var c=splitString[k].substring(6,splitString[k].indexOf("<",5));
	var re=splitString[k].substring(0,splitString[k].indexOf("</rgb>",1)+6);
var reg3=new RegExp(re,"g");
myspan.getStyle().setProperty('fill',c);
splitString[k]=splitString[k].replace(re,"");
}		
myspan.appendChild(svgdoc.createTextNode(splitString[k]));
svgobj.appendChild(myspan);}
	}
var txtlen=9+svgobj.getBBox().width;
var svgobj = svgdoc.getElementById (infotiprect);
svgobj.setAttributeNS(null,'x', (evt.getClientX()-offsetXmap)/ratio+5.5);
svgobj.setAttributeNS(null,'y', (evt.getClientY()-offsetYmap)/ratio-9.5);
svgobj.setAttributeNS(null,'width', txtlen);
if (nbsplit >1) svgobj.setAttributeNS(null,'height',1.1*nbsplit+'em');else svgobj.setAttribute ('height',1+'em');
svgobj.setAttributeNS(null,'rx', 3);
svgobj.setAttributeNS(null,'ry', 3);
var svgstyle = svgobj.getStyle();
svgstyle.setProperty('visibility', 'visible');
}

function hideinfotip(evt)
	{
	var infotip = 'infotip';
	var infotiprect = 'infotipRect';
	var svgobj = svgdoc.getElementById (infotip);
	if (nbsplit == 1)
		{
		svgobj.style.setProperty ('visibility', 'hidden');
		}
	else
		{
		nodelist = svgobj.getChildNodes();
		for (k=nbsplit-1; k>0; k--)
			{
			if(nodelist.item(k)!="")
			{
			svgobj.removeChild(nodelist.item(k))
			}
			}
		svgobj.style.setProperty ('visibility', 'hidden');
	}
	svgdoc.getElementById(infotiprect).style.setProperty ('visibility', 'hidden');
	nbsplit="0";
}

function Zoomto(evt,tovalue,x)
{
	
	cleanzoombar(evt);
	var oldbarzoom = svgdoc.getElementById('rectzoom'+theZoom);
	oldbarzoom.setAttributeNS(null,'stroke','url(#survol)');
	var barzoom = svgdoc.getElementById('rectzoom'+tovalue);
	barzoom.setAttributeNS(null,'stroke','url(#zoomselect)');
	svgdoc.getElementById('zoomcursor').setAttributeNS(null,'transform','translate('+ parseFloat(parseInt(tovalue)*4.5/intervale + 686.75 -zoommin*4.5/intervale) +',199)');
	zoomVal = parseInt(tovalue);
	theZoom  = zoomVal;
	rectOveXcorner = parseFloat(svgRect.getAttributeNS(null,'x'));
	rectOveYcorner = parseFloat(svgRect.getAttributeNS(null,'y'));
	rectOvewidth = parseFloat(svgRect.getAttributeNS(null,'width'));
	rectOveheight = parseFloat(svgRect.getAttributeNS(null,'height'));
	if(cx!=0)
	{
	xcenter=cx;
	ycenter=cy;
	cx=0;cy=0;
	}
	else
	{
	xcenter = rectOveXcorner + rectOvewidth / 2;
	ycenter = rectOveYcorner + rectOveheight / 2;
	
	}
	
	nWidth = largeurini * (100/zoomVal);
	xnulcorner = xcenter - nWidth/ 2;
	nHeight = (nWidth/ratiovue);
	ynulcorner = ycenter - nHeight/2
	Rect1lar = parseFloat(Rect1.getAttributeNS(null,'width'));
	Rect1hau =Rect1lar/ratiovue;
	if ((zoomtool == 'zoomin') && (Rpressed != 0))
		{
		svgRectee = svgdoc.getElementById('rectevt');
		xnulcorner = parseFloat(svgRectee.getAttributeNS(null,'x'));
	    ynulcorner  = parseFloat(svgRectee.getAttributeNS(null,'y'));
		wnul=parseFloat(svgRectee.getAttributeNS(null,'width'));
		hnul= parseFloat(svgRectee.getAttributeNS(null,'height'));
		xnulcorner=(rectOveXcorner+((xnulcorner-11)*rectOvewidth/larmap));
		ynulcorner=(rectOveYcorner+((ynulcorner-11)*rectOveheight/haumap));
		xcenter = xnulcorner + nWidth/2;
		ycenter = ynulcorner + nHeight/2;
		}
	svgRect.setAttributeNS(null,'x',xnulcorner);
	var xnu=xnulcorner;
	svgRect.setAttributeNS(null,'y',ynulcorner);
	var ynu=ynulcorner;
	
	svgRect.setAttributeNS(null,'width',nWidth);
	svgRect.setAttributeNS(null,'height',nHeight);
	svgRect.setAttributeNS(null,'visibility','visible');
	newViewport = xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
	svgMainViewport.setAttributeNS(null,'viewBox',newViewport);
if (zoomVal>600)
	{
	svgdoc.getElementById('Rect1').setAttributeNS(null,'visibility','visible');
	svgdoc.getElementById('lin1').setAttributeNS(null,'visibility','visible');
	svgdoc.getElementById('lin2').setAttributeNS(null,'visibility','visible');
	if(overini==0)
	{
	overini=1;
	Rect1.setAttributeNS(null,'x',xcenter-((Rect1lar/6)/2));
	Rect1.setAttributeNS(null,'y',ycenter-((Rect1hau/6)/2));
	Rect1.setAttributeNS(null,'width',Rect1lar/6);
	Rect1.setAttributeNS(null,'height',Rect1hau/6);
	lin1.setAttributeNS(null,'x',xcenter-((Rect1lar/6)/2));
	lin1.setAttributeNS(null,'y',ycenter);
	lin1.setAttributeNS(null,'width',Rect1lar/6);
	
	lin2.setAttributeNS(null,'x',xcenter);
	lin2.setAttributeNS(null,'y',ycenter-((Rect1hau/6)/2));
	lin2.setAttributeNS(null,'height',Rect1hau/6);
	}
	else
	{
		Rect1.setAttributeNS(null,'x',xcenter-(parseFloat(Rect1.getAttributeNS(null,'width'))/2));
		Rect1.setAttributeNS(null,'y',ycenter-(parseFloat(Rect1.getAttributeNS(null,'height'))/2));																	   		lin1.setAttributeNS(null,'x',xcenter-(parseFloat(Rect1.getAttributeNS(null,'width'))/2));
		lin1.setAttributeNS(null,'y',ycenter);
		lin2.setAttributeNS(null,'x',xcenter);
		lin2.setAttributeNS(null,'y',ycenter-(parseFloat(Rect1.getAttributeNS(null,'height'))/2));											   }
	}
	else
	{
	svgdoc.getElementById('Rect1').setAttributeNS(null,'visibility','hidden');
	svgdoc.getElementById('lin1').setAttributeNS(null,'visibility','hidden');
	svgdoc.getElementById('lin2').setAttributeNS(null,'visibility','hidden');
	}
	
	if (zoomVal>zoommin)
		{
		var zoomin=svgdoc.getElementById('moins');
		zoomin.setAttributeNS(null,'pointer-events','visible');
		zoomin.setAttributeNS(null,'opacity','1');
		}
	else
		{
		zoomin=svgdoc.getElementById('moins');
		zoomin.setAttributeNS(null,'pointer-events','none');
		zoomin.setAttributeNS(null,'opacity','0.2');
		}
	if (zoomVal==zoommax)
		{
		zoomin=svgdoc.getElementById('plus');
		zoomin.setAttributeNS(null,'pointer-events','none');
		zoomin.setAttributeNS(null,'opacity','0.2');
		}
	else
		{
		zoomin=svgdoc.getElementById('plus');
		zoomin.setAttributeNS(null,'pointer-events','visible');
		zoomin.setAttributeNS(null,'opacity','1');
		}
		lecture_control()
		
}

function HTTP() 
{
var xmlhttp
  if (!xmlhttp) {
 				 try {
   						xmlhttp = new XMLHttpRequest();
  					} 
					catch (e) 
					{
				   xmlhttp=false
 					 }
 				}
 return xmlhttp
}

if (typeof getURL=='undefined') {
 getURL=function(url,fn) { 
  var xmlhttp=new HTTP();
  if (xmlhttp) {
   xmlhttp.open("GET",url,true);
   xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
     fn({status:xmlhttp.status,content:xmlhttp.responseText,
      contentType:xmlhttp.getResponseHeader("Content-Type")})
    }
   }
   xmlhttp.send()
  } else {
   //Some Appropriate Fallback...
  }
 }
}
if (typeof postURL=='undefined') {
  postURL=function(url,txt,fn,type,enc) {
  var xmlhttp=new HTTP();
  if (xmlhttp) {
   xmlhttp.open("POST",url,true);
   if (enc) xmlhttp.setRequestHeader("Content-Encoding",enc)
   if (type) xmlhttp.setRequestHeader("Content-Type",type)
   xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
					try {		
      fn({success:xmlhttp.status,content:xmlhttp.responseText,
      	contentType:xmlhttp.getResponseHeader("Content-Type")})
					} catch (e) {
						fn.operationComplete({success:xmlhttp.status,content:xmlhttp.responseText,contentType:xmlhttp.getResponseHeader("Content-Type")})
     }
    }
   }
   xmlhttp.send(txt)
  } else {
   //Some Appropriate Fallback...
  }
 }
 if (typeof printNode=='undefined') {
printNode= function (el) {
   return (new XMLSerializer()).serializeToString(el);
}}
 
}
function cleanzoombar(evt)
{
for (var i=0; i<19; i++)
	{
	oldbarzoom = svgdoc.getElementById('rectzoom'+parseInt(zoommin+i*intervale));
	oldbarzoom.setAttributeNS(null,'fill','url(#hors)');
	}
}

function clear(id1)
{
while (document.getElementById(id1).hasChildNodes())
{
document.getElementById(id1).removeChild(document.getElementById(id1).getFirstChild());
}
}

function extract(libelle,couche,id,raster)
{
	var reg=new RegExp("[;]", "g");
	var tablayers=couche.split(reg);	
	var nblayer = tablayers.length ;
if(nblayer>1)
		{
			if(svgdoc.getElementById('control'+parseInt(tablayers[0])).getStyle().getPropertyValue('visibility') == 'visible')
				{
				svgdoc.getElementById('control'+parseInt(tablayers[0])).getStyle().setProperty('visibility','hidden');
				svgdoc.getElementById('tra'+parseInt(tablayers[0])).getStyle().setProperty('visibility','hidden');
							var cher=new RegExp("[|]", "g");
								var tableau=libelle.split(cher);
								for (var i=0; i<tableau.length; i++) 
								{
									suppvarraster(tableau[i])
 								}
								if(raster=='')
								{
									for (var i=0; i<tableau.length; i++) 
								{
 								zlayer[tableau[i]].svg_visible='false';
								}
									
								}
						lecture_control()	
							for(i=0;i<nblayer;i++)
							{
							svgdoc.getElementById('control'+tablayers[i]).getStyle().setProperty('visibility','hidden');
							svgdoc.getElementById('tra'+tablayers[i]).getStyle().setProperty('visibility','hidden');
							}
					
				}
			else
			{
			svgdoc.getElementById('control'+parseInt(tablayers[0])).getStyle().setProperty('visibility','visible');
			svgdoc.getElementById('tra'+parseInt(tablayers[0])).getStyle().setProperty('visibility','visible');
					if(raster=="raster")
							{	
								coucherastervisible+=libelle+";";
							}
					else
							{
								var cher=new RegExp("[|]", "g");
								var tableau=libelle.split(cher);
								for (var i=0; i<tableau.length; i++) 
								{
									zlayer[tableau[i]].svg_visible='true';
								}
							}
							lecture_control()
				if(svgdoc.getElementById('d'+parseInt(tablayers[0])).getStyle().getPropertyValue('visibility') == 'hidden')
				{
					for(i=0;i<nblayer;i++)
					{
						svgdoc.getElementById('control'+tablayers[i]).getStyle().setProperty('visibility','visible');
						svgdoc.getElementById('tra'+tablayers[i]).getStyle().setProperty('visibility','visible');
					}
				}
				else
				{
					for(i=0;i<nblayer;i++)
					{
						svgdoc.getElementById('control'+tablayers[i]).getStyle().setProperty('visibility','visible');
					}
				}
			}
		}
		else
		{
			if(svgdoc.getElementById('control'+couche).getStyle().getPropertyValue('visibility') == 'visible')
			{
				svgdoc.getElementById('control'+couche).getStyle().setProperty('visibility','hidden');
				svgdoc.getElementById('tra'+couche).getStyle().setProperty('visibility','hidden');
			
								suppvarraster(libelle)
								if(raster=='')
								{
								zlayer[libelle].svg_visible='false';
								}
								lecture_control()
								
								
			}
			
			else
			{
				svgdoc.getElementById('control'+couche).getStyle().setProperty('visibility','visible');
				svgdoc.getElementById('tra'+couche).getStyle().setProperty('visibility','visible');
						if(raster=="raster")
							{	
								coucherastervisible+=libelle+";";
							}
						else
							{
								zlayer[libelle].svg_visible='true';
							}
							lecture_control()
			}
		}
}

function extraction(x)
{
	
	if(x=="controlraster")
	{
		var reg=new RegExp("( )", "g");
	coucherasteraenvoyer=coucherastervisible.replace(reg,"_");
	coucherasteraenvoyer=coucherasteraenvoyer.substr(0,coucherasteraenvoyer.length-1)
	var url="charge.php?type=raster&raster="+coucherasteraenvoyer+"&layer=" + x + "&x="+ xnulcorner + "&y=" + ynulcorner + "&lar=" + nWidth  + "&hau=" + nHeight+ "&zoom=" + zoomVal  + "&sessionname=" + sessionname + "&sessionid=" + sessionid + "&xini=" + xini + "&yini=" + yini ;
	animation()
	getURL(url,retour_extract)
	}
	else
	{
		if(nav==1)
	{
cou=convasc(couchesvgvisible);
var url='charge.php?type=svg&raster='+ cou +'&layer=' + x + '&x='+ xnulcorner + '&y=' + ynulcorner + '&lar=' + nWidth  + '&hau=' + nHeight+ '&zoom=' + zoomVal  + '&sessionname=' + sessionname + '&sessionid=' + sessionid + '&xini=' + xini + '&yini=' + yini+ '&nav=' + nav + '&placid=' + placid;
	}
	else	{	
	var url='charge.php?type=svg&raster='+couchesvgvisible+'&layer=' + x + '&x='+ xnulcorner + '&y=' + ynulcorner + '&lar=' + nWidth  + '&hau=' + nHeight+ '&zoom=' + zoomVal  + '&sessionname=' + sessionname + '&sessionid=' + sessionid + '&xini=' + xini + '&yini=' + yini+ '&nav=' + nav + '&placid=' + placid;}
	animation()
	//alert(url)
	getURL(url,retour_extract)
	couchesvgvisible="";
	}
}
function convasc_inverse(data)
{
	
		var res='';
		var reg=new RegExp("chr224", "g");
		var reg1=new RegExp("chr233", "g");
		var reg2=new RegExp("chr232", "g");
		var reg3=new RegExp("chr234", "g");
		var reg4=new RegExp("chr226", "g");
		var reg5=new RegExp("chr231", "g");
		var reg6=new RegExp("chr244", "g");
		var reg7=new RegExp("chr238", "g");
		var reg8=new RegExp("chr251", "g");
		res=data.replace(reg,String.fromCharCode(224));
		res=res.replace(reg1,String.fromCharCode(233));
		res=res.replace(reg2,String.fromCharCode(232));
		res=res.replace(reg3,String.fromCharCode(234));
		res=res.replace(reg4,String.fromCharCode(226));
		res=res.replace(reg5,String.fromCharCode(231));
		res=res.replace(reg6,String.fromCharCode(244));
		res=res.replace(reg7,String.fromCharCode(238));
		res=res.replace(reg8,String.fromCharCode(251));
		return res
}
function convasc(data)
{
		var res='';
		var reg=new RegExp(String.fromCharCode(224), "g");
		var reg1=new RegExp(String.fromCharCode(233), "g");
		var reg2=new RegExp(String.fromCharCode(232), "g");
		var reg3=new RegExp(String.fromCharCode(234), "g");
		var reg4=new RegExp(String.fromCharCode(226), "g");
		var reg5=new RegExp(String.fromCharCode(231), "g");
		var reg6=new RegExp(String.fromCharCode(244), "g");
		var reg7=new RegExp(String.fromCharCode(238), "g");
		var reg8=new RegExp(String.fromCharCode(251), "g");
		var reg9=new RegExp(String.fromCharCode(60), "g");
		var reg10=new RegExp(String.fromCharCode(62), "g");
		var reg11=new RegExp(String.fromCharCode(63), "g");
		var reg12=new RegExp(String.fromCharCode(34), "g");
		var reg13=new RegExp(String.fromCharCode(39), "g");
		var reg14=new RegExp(String.fromCharCode(35), "g");
		var reg15=new RegExp(String.fromCharCode(33), "g");
		var reg16=new RegExp(String.fromCharCode(95), "g");
		res=data.replace(reg,"chr(224)");
		res=res.replace(reg1,"chr(233)");
		res=res.replace(reg2,"chr(232)");
		res=res.replace(reg3,"chr(234)");
		res=res.replace(reg4,"chr(226)");
		res=res.replace(reg5,"chr(231)");
		res=res.replace(reg6,"chr(244)");
		res=res.replace(reg7,"chr(238)");
		res=res.replace(reg8,"chr(251)");
		res=res.replace(reg9,"chr(60)");
		res=res.replace(reg10,"chr(62)");
		res=res.replace(reg11,"chr(63)");
		res=res.replace(reg12,"chr(34)");
		res=res.replace(reg13,"chr(39)");
		res=res.replace(reg14,"chr(35)");
		res=res.replace(reg15,"chr(33)");
		res=res.replace(reg16,"chr(95)");
		return res
}

function retour_extract(data)
{
	 var string = '';
    if(data.success) 
	{
    string = data.content;
	//alert(string)
	if(string!="")
	{
	var docFragment=parseXML(string, document);
	var frag=docFragment.firstChild;
	var elem=frag.getAttributeNS(null,"id");
	var content = document.getElementById(elem);
    content.appendChild(docFragment);
	cache()
	}
	else
	{
	cache()	
	message('erreur...')
	}
	}
}
 
 function lecture_control(id)
{
var nb = layer.length;
n=nblayer;
for(a=0;a<n;a++)
{
	r=a+1;
	if(controllay[a].zoommax<zoomVal || controllay[a].zoommin>zoomVal)
	{
		if(svgdoc.getElementById("theme" + r).getAttribute("so")>0)
			{
				l=svgdoc.getElementById("theme" + r).getAttribute("so")
				if(svgdoc.getElementById("d" + r).getStyle().getPropertyValue("visibility") == "hidden")
					{
						for(z=1;z<=l;z++)
							{
							svgdoc.getElementById("tra"+r+codalph(z)).getStyle().setProperty('visibility','hidden')
							}
						svgdoc.getElementById("d" + r).getStyle().setProperty('visibility','visible');
						svgdoc.getElementById('soustheme'+r).getStyle().setProperty('visibility','hidden');
						svgdoc.getElementById('theme'+(r+1)).setAttributeNS(null,'transform','translate(0,0)');
					}
					for(z=1;z<=l;z++)
						{
							svgdoc.getElementById("control"+r+codalph(z)).getStyle().setProperty('visibility','hidden');
							vari=svgdoc.getElementById("coche"+r+codalph(z)).getAttribute("onclick");
							vari=vari.substr(9,vari.length-1);
							var exp=new RegExp(",","g");
							var tabvari=vari.split(exp);
							vari=tabvari[0].substring(0,tabvari[0].length-1);
							suppvarraster(vari)
							if(controllay[a].type=='')
								{
								zlayer[vari].svg_visible='false'
								}
						}
						
			}
			else
			{
				vari=svgdoc.getElementById("coche" + r).getAttribute("onclick");
				vari=vari.substr(9,vari.length-1);
				var exp=new RegExp(",","g");
				var tabvari=vari.split(exp);
				vari=tabvari[0].substring(0,tabvari[0].length-1);
				//alert(vari)
				if(controllay[a].type=='')
								{
								zlayer[vari].svg_visible='false'
								}
				suppvarraster(vari)
			}
			
			svgdoc.getElementById('com'+r).getStyle().setProperty('opacity','0.5');
			svgdoc.getElementById('com'+r).getStyle().setProperty('pointer-events','none');
			svgdoc.getElementById("control"+r).getStyle().setProperty('visibility','hidden');
			svgdoc.getElementById("tra"+r).getStyle().setProperty('visibility','hidden');
			
			
	}
	else
	{
		svgdoc.getElementById('com'+r).getStyle().setProperty('opacity','1');
		svgdoc.getElementById('com'+r).getStyle().setProperty('pointer-events','visible');
	}
}

	for (b=0;b<nb;b++)
	{
	var axex="nok";
	var axey="nok";
		if(zlayer[layer[b]].svg_visible=='true')
		{
			if(zlayer[layer[b]].svg_zoomraster>zoomVal || ((nWidth*3300/620)>5221 && zlayer[layer[b]].svg_partiel=='1'))
			{
				clear('control'+zlayer[layer[b]].svg_controle)
				zlayer[layer[b]].svg_zoom_charge=''
				suppvarraster(layer[b])	
				coucherastervisible+=layer[b]+";";
				
			}
			else
			{
								suppvarraster(layer[b])
								if(zlayer[layer[b]].svg_zoom_charge=='')
								{
									couchesvgvisible=layer[b];
									zlayer[layer[b]].svg_zoom_charge=zoomVal;
									zlayer[layer[b]].svg_position=xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
									clear('control'+zlayer[layer[b]].svg_controle)
									extraction('control'+zlayer[layer[b]].svg_controle);
								}
								else if(zlayer[layer[b]].svg_zoom_charge<=zoomVal || zlayer[layer[b]].svg_partiel!='1')
								{
									var cher=new RegExp("[ ]+", "g");
									var tableau=zlayer[layer[b]].svg_position.split(cher);
									lar1=parseFloat(tableau[0])+parseFloat(tableau[2]);
									lar2=xnulcorner+nWidth;
									hau1=parseFloat(tableau[1])+parseFloat(tableau[3]);
									hau2=ynulcorner+nHeight;
									
									if(parseFloat(tableau[0])<=xnulcorner && lar1>=lar2)
									{
										axex='ok'
									}
									if(parseFloat(tableau[1])<=ynulcorner && hau1>=hau2)
									{
										axey='ok'
									}
									if((axex!='ok' || axey!='ok') && zlayer[layer[b]].svg_partiel=='1')
									{
										couchesvgvisible=layer[b];
										zlayer[layer[b]].svg_zoom_charge=zoomVal;
										zlayer[layer[b]].svg_position=xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
										clear('control'+zlayer[layer[b]].svg_controle)
										extraction('control'+zlayer[layer[b]].svg_controle);
									}
									
								}
								else
								{
									couchesvgvisible=layer[b];
										zlayer[layer[b]].svg_zoom_charge=zoomVal;
										zlayer[layer[b]].svg_position=xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
										clear('control'+zlayer[layer[b]].svg_controle)
										extraction('control'+zlayer[layer[b]].svg_controle);
								}
								
				
			}
		}
	}
	if((coucherastervisible=="" || coucherastervisible==";" || coucherastervisible==";;") && couraster==0)
	{
	clear('controlraster')
	couraster=1;
	coucherastervisible="";
	}
	if(coucherastervisible!="" && coucherastervisible!=";" && coucherastervisible!=";;")
							{
							posiactu=xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
							if(posiactu!=coucherasterposi || coucherastervisible!=coucherasterold)
							{
							clear('controlraster')
							extraction('controlraster');
							couraster=0;
							coucherasterposi=xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
							coucherasterold=coucherastervisible;
							}
							}
							else
							{
							coucherasterposi="";
							coucherasterold=""
							}
}

function suppvarraster(x)
{
var exp=new RegExp("[;]+","g");
var tabrasteur=coucherastervisible.split(exp);
for (var i=0;i<tabrasteur.length;i++)
{
	if(tabrasteur[i]==x)
	{
	tabrasteur.splice(i,1);
	}
}
coucherastervisible="";
if(tabrasteur.length>0)
{
for (var i=0;i<tabrasteur.length;i++)
{
coucherastervisible+=tabrasteur[i]+";";	
}
}
if(coucherastervisible==";" || coucherastervisible==";;")
{
coucherastervisible="";
}
}

function animation(evt)
{
svgdoc.getElementById("message_box").setAttribute("visibility","visible");
var animations = svgdoc.getElementsByTagName('animateTransform');
for ( var i = 0; i < animations.length; i++ ) 
{ 
animations.item(i).beginElement();
 } 
 svgdoc.getElementById("message_box").setAttribute("visibility","visible");
}
function cache()
{
var animations = svgdoc.getElementsByTagName('animateTransform');
animations.close;
svgdoc.getElementById("message_box").setAttribute("visibility","hidden");
}

function liste_scrolling(pas)
{liste_scroll_decale+=pas;
var larg=svgdoc.getElementById("scroll_cursor").getAttribute("height");
if ((liste_scroll_decale>=0)&&(liste_scroll_decale<=(291-larg)/12))
{node=svgdoc.getElementById("layer");
node.setAttributeNS(null,'transform','translate(0,'+Math.round((-12*liste_scroll_decale))+')');
var y_pos=parseInt(liste_scroll_decale);
svgdoc.getElementById("curseur").setAttributeNS(null,'transform','translate(0,'+y_pos*12+')')
}
else
{
liste_scroll_decale-=pas
}
}

function liste_glisse_click(evt,valeur)
{scroll_y=evt.getClientY();
liste_scroll_appui=valeur}

function liste_glisse(evt,x)
{
if (liste_scroll_appui=="true")
{
	if(x=='scroll_cursor1')
	{
		var larg=svgdoc.getElementById("scroll_cursor1").getAttribute("height");
		var ym=evt.getClientY();
		var decale=parseInt((ym-scroll_y))/larg;
		if (decale!=0)
		{
		liste_scroll_decale+=decale;
		if (liste_scroll_decale<0)
 		{
		liste_scroll_decale=0
		}
		if (liste_scroll_decale>(120-larg)/2)
		{
		liste_scroll_decale=(120-larg)/2
		}
		node=svgdoc.getElementById("valfixe");
		node.setAttributeNS(null,'transform','translate(0,'+Math.round((-10*liste_scroll_decale))+')');
		var y_pos=parseInt(liste_scroll_decale);
		svgdoc.getElementById("curseur1").setAttributeNS(null,'transform','translate(0,'+y_pos*2+')')
		}
	}
	else if(x=='scroll_cursor_deroul')
	{
		var larg=svgdoc.getElementById("scroll_cursor_deroul").getAttribute("height");
		
		var ym=evt.getClientY();
		var decale=parseInt((ym-scroll_y))/larg;
		//var decale=parseInt((ym-scroll_y))
		if (decale!=0)
		{
		liste_scroll_decale+=decale;
		
		if (liste_scroll_decale<0)
 		{
		liste_scroll_decale=0
		}
		if (liste_scroll_decale>(200-larg))
		{
		
		liste_scroll_decale=(200-larg)
		}
		node=svgdoc.getElementById("tderoul");
		node.setAttributeNS(null,'transform','translate(0,'+Math.round((-7*liste_scroll_decale))+')');
		var y_pos=parseInt(liste_scroll_decale);
		svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'transform','translate(0,'+y_pos+')')
		}
	}
	else
	{
		var larg=svgdoc.getElementById("scroll_cursor").getAttribute("height");
		var ym=evt.getClientY();
		var decale=parseInt((ym-scroll_y))/larg;
		if (decale!=0)
		{
		liste_scroll_decale+=decale;
		if (liste_scroll_decale<0)
 		{
		liste_scroll_decale=0
		}
		if (liste_scroll_decale>(291-larg)/12)
		{
		liste_scroll_decale=(291-larg)/12
		}
		node=svgdoc.getElementById("layer");
		node.setAttributeNS(null,'transform','translate(0,'+Math.round((-12*liste_scroll_decale))+')');
		var y_pos=parseInt(liste_scroll_decale);
		svgdoc.getElementById("curseur").setAttributeNS(null,'transform','translate(0,'+y_pos*12+')')
		}
	}

}}

function Zoomless(evt)
{
zoomVal = zoomVal - intervale;
Zoomto(evt,zoomVal);
}

function Zoommore(evt)
{
zoomVal = zoomVal + intervale;
Zoomto(evt,zoomVal);
}

function beginPan(evt) 
{
	pressed = 1;
	rectOvewidth = parseFloat(svgRect.getAttributeNS(null,'width'));
	rectOveheight = parseFloat(svgRect.getAttributeNS(null,'height'));
	evtX = parseFloat(evt.getClientX());
	evtY = parseFloat(evt.getClientY());
	evtX =(evtX-offsetXmap)/ratio;
	evtY =(evtY-offsetYmap)/ratio;
	rectUlXCorner = parseFloat(svgRect.getAttributeNS(null,'x'));
	rectUlYCorner = parseFloat(svgRect.getAttributeNS(null,'y'));
	rect1Ovewidth = parseFloat(Rect1.getAttributeNS(null,'width'));
	rect1Oveheight = parseFloat(Rect1.getAttributeNS(null,'height'));
	rect1UlXCorner = parseFloat(Rect1.getAttributeNS(null,'x'));
	rect1UlYCorner = parseFloat(Rect1.getAttributeNS(null,'y'));
}

function doPan(evt)
{
	if (pressed == 1) 
		{
		overviewViewport = svgdoc.getElementById("overviewmap");		
		pluginPixWidth = overviewViewport.getAttribute("width");
		pluginPixHeight = overviewViewport.getAttribute("height");
		var allWidth = largeurini;
		var allHeight = hauteurini;
		newEvtX = parseFloat(evt.getClientX());
		newEvtY = parseFloat(evt.getClientY());
		newEvtX =(newEvtX-offsetXmap)/ratio;
		newEvtY =(newEvtY-offsetYmap)/ratio;
		toMoveX = rectUlXCorner + (newEvtX - evtX)* allWidth / pluginPixWidth;
		toMoveY = rectUlYCorner+(newEvtY - evtY)* allHeight / pluginPixHeight;
		toMoveX1 = rect1UlXCorner + (newEvtX - evtX)* allWidth / pluginPixWidth;
		toMoveY1 = rect1UlYCorner+(newEvtY - evtY)* allHeight / pluginPixHeight;
		if (toMoveX < rectXcorner) 
		{
			svgRect.setAttributeNS(null,'x',rectXcorner);
			Rect1.setAttributeNS(null,'x',rectXcorner+(rectOvewidth/2)-(rect1Ovewidth/2));
			lin1.setAttributeNS(null,'x',rectXcorner+(rectOvewidth/2)-(rect1Ovewidth/2));
			lin2.setAttributeNS(null,'x',rectXcorner+(rectOvewidth/2));
			}
		else if ((toMoveX + rectOvewidth) >allWidth) 
			{
			svgRect.setAttributeNS(null,'x',rectXcorner + allWidth - rectOvewidth);
			Rect1.setAttributeNS(null,'x',rectXcorner + allWidth - rectOvewidth+(rectOvewidth/2)-(rect1Ovewidth/2));
			lin1.setAttributeNS(null,'x',rectXcorner + allWidth - rectXcorner+(rectOvewidth/2)-(rect1Ovewidth/2));
			lin2.setAttributeNS(null,'x',rectXcorner + allWidth - rectXcorner+(rectOvewidth/2));
			}
		else 
			{
			svgRect.setAttributeNS(null,'x',toMoveX);
			Rect1.setAttributeNS(null,'x',toMoveX1);
			lin1.setAttributeNS(null,'x',toMoveX1);
			lin2.setAttributeNS(null,'x',toMoveX1+(rect1Ovewidth/2));
			}
		if (toMoveY < rectYcorner) 
		{
			svgRect.setAttributeNS(null,'y',rectYcorner);
			Rect1.setAttributeNS(null,'y',rectYcorner+(rectOveheight/2)-(rect1Oveheight/2));
			lin1.setAttributeNS(null,'y',rectYcorner+(rectOveheight/2));
			lin2.setAttributeNS(null,'y',rectYcorner+(rectOveheight/2)+(rect1Oveheight/2));
			
			}
		else if ((toMoveY + rectOveheight) > (allHeight)) 
		
			{
			svgRect.setAttributeNS(null,'y',rectYcorner + allHeight - rectOveheight);
			Rect1.setAttributeNS(null,'y',rectYcorner + allHeight - rectOveheight+(rectOveheight/2)-(rect1Oveheight/2));
			lin1.setAttributeNS(null,'y',rectYcorner + allHeight - rectYcorner+(rectOveheight/2));
			lin2.setAttributeNS(null,'y',rectYcorner + allHeight - rectYcorner+(rectOveheight/2)+(rect1Oveheight/2));
			}
		else 
			{
			svgRect.setAttributeNS(null,'y',toMoveY);
			Rect1.setAttributeNS(null,'y',toMoveY1);
			lin1.setAttributeNS(null,'y',toMoveY1+(rect1Oveheight/2));
			lin2.setAttributeNS(null,'y',toMoveY1);
			}
		evtX = newEvtX;
		evtY = newEvtY;
		rectUlXCorner = parseFloat(svgRect.getAttributeNS(null,'x'));
		rectUlYCorner = parseFloat(svgRect.getAttributeNS(null,'y'));
		rect1UlXCorner = parseFloat(Rect1.getAttributeNS(null,'x'));
		rect1UlYCorner = parseFloat(Rect1.getAttributeNS(null,'y'));
		}
}

function endPan(evt) 
	{
	rectOveXcorner = parseFloat(svgRect.getAttributeNS(null,'x'));
	rectOveYcorner = parseFloat(svgRect.getAttributeNS(null,'y'));
	y = svgMainViewport.currentTranslate.y;
	x = svgMainViewport.currentTranslate.x;
	ynulcorner = parseFloat(rectOveYcorner + (y*100/zoomVal));
	xnulcorner = parseFloat(rectOveXcorner + (x*100/zoomVal));
	nWidth = parseFloat(svgRect.getAttributeNS(null,'width'));
	nHeight = parseFloat(svgRect.getAttributeNS(null,'height'));
	xcenter = rectOveXcorner + rectOvewidth / 2;
	ycenter = rectOveYcorner + rectOveheight / 2;
	newViewport = xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
	svgMainViewport.setAttributeNS(null,'viewBox',newViewport);
if (pressed == 1)
		{
			lecture_control()
		}
	pressed = 0;
}

function goEast(evt)
{
	if (theZoom !=100) 
		{
		
		x = svgMainViewport.currentTranslate.x;
		rectX = parseFloat(svgRect.getAttributeNS(null,'x'));
		w =  parseFloat(svgRect.getAttributeNS(null,'width'));
		z= rectX + w;
		if (z < (largeurini))
			{
			attribution('x',rectX,'+')
			lecture_control()
			}
		}
}

function goWest(evt)
	{
	if (theZoom !=100)
		{
		
		x = svgMainViewport.currentTranslate.x;
		rectX = parseFloat(svgRect.getAttributeNS(null,'x'));
		if (rectX > 0)
			{
			attribution('x',rectX,'-')
			lecture_control()
			}
		}
}

function goNorth(evt)
{
	if (theZoom!=100)
		{
		rectY = parseFloat(svgRect.getAttributeNS(null,'y'));
		if (rectY > 0)
			{
			attribution('y',rectY,'-')
			lecture_control()
			}
		}
}

function goSouth(evt)
{
	if (theZoom !=100) 
		{
		rectY = parseFloat(svgRect.getAttributeNS(null,'y'));
		h= parseFloat(svgRect.getAttributeNS(null,'height'));
		z= rectY+h;
			if (z < (hauteurini)) 
			{
			attribution('y',rectY,'+')
lecture_control()
			}
		}
}

function attribution(pos,rectval,signe)
{
	rectX = parseFloat(svgRect.getAttributeNS(null,pos));
		if(pos=='x')
		{
		att =  parseFloat(svgRect.getAttributeNS(null,'width'));
		}
		else
		{
		att =  parseFloat(svgRect.getAttributeNS(null,'height'));	
		}
		if(signe=='+')
		{
		rectX = rectval + att/6
		l1=-att/6;
		l2=l1;
		r1=l1;
		}
		else
		{
		rectX = rectval - att/6
		l1=att/6;
		l2=l1;
		r1=l1;
		}
			svgRect.setAttributeNS(null,pos, rectX);
			rectOveXcorner = parseFloat(svgRect.getAttributeNS(null,'x'));
			rectOveYcorner = parseFloat(svgRect.getAttributeNS(null,'y'));
			y = svgMainViewport.currentTranslate.y;
			x = svgMainViewport.currentTranslate.x;
			ynulcorner = parseFloat(rectOveYcorner + (y*100/zoomVal));
			xnulcorner  = parseFloat(rectOveXcorner + (x*100/zoomVal));
			nWidth = parseFloat(svgRect.getAttributeNS(null,'width'));
			nHeight = parseFloat(svgRect.getAttributeNS(null,'height'));
			newViewport = xnulcorner + ' ' + ynulcorner + ' ' + nWidth + ' ' + nHeight;
			svgMainViewport.setAttributeNS(null,'viewBox',newViewport);
			lin1 = svgdoc.getElementById('lin1');
			xlin1 = parseFloat(lin1.getAttributeNS(null,pos));
	lin1.setAttributeNS(null,pos,(xlin1- l1));
	lin2 = svgdoc.getElementById('lin2');
	xlin2 = parseFloat(lin2.getAttributeNS(null,pos));
	lin2.setAttributeNS(null,pos,(xlin2 - l2));
	rect1 = svgdoc.getElementById('Rect1');
	xrect1 = parseFloat(rect1.getAttributeNS(null,pos));
	rect1.setAttributeNS(null,pos,(xrect1 - r1));
}

function Zoomin(evt)
{

	objbgrectevt = svgdoc.getElementById('bgrectevt');
	objbgrectevt.getStyle().setProperty('pointer-events','visible');
	objbgrectevt.getStyle().setProperty('visibility','visible');
	svgdoc.getElementById('loupe').getStyle().setProperty('opacity','0.5');
	svgdoc.getElementById('loupe').getStyle().setProperty('pointer-events','none');
	zoomtool = 'zoomin';
	svgdoc.getElementById('releasezoom').getStyle().setProperty('visibility','visible');
	svgdoc.getElementById('releasezoom').getStyle().setProperty('pointer-events','visible');
}

function beginResize(evt)
{
RevtX = evt.getClientX();
RevtY = evt.getClientY();
var x0 = parseFloat(svgMainViewport.getAttributeNS(null,'x'));
var y0 = parseFloat(svgMainViewport.getAttributeNS(null,'y'));
RevtX =(RevtX-offsetXmap)/ratio;
RevtY =(RevtY-offsetYmap)/ratio;
	if (zoomtool == 'zoomin')
		{
		var objrectevt;
		if ((RevtX > 11) && (RevtX < larmap+11) && (RevtY> 11 ) && (RevtY <=  haumap+11))
			{
			Rpressed = 1;
			objrectevt = svgdoc.getElementById('rectevt');
			objrectevt.setAttributeNS(null,'x',RevtX);
			objrectevt.setAttributeNS(null,'y',RevtY);
			}
	}
}

function doResize(evt){
if ((Rpressed == 1) && (zoomtool == 'zoomin'))
	{
	RnewevtX = evt.getClientX();
	RnewevtY = evt.getClientY();
RnewevtX =(RnewevtX-offsetXmap)/ratio;
RnewevtY =(RnewevtY-offsetYmap)/ratio;
objrectevt = svgdoc.getElementById('rectevt');
	if ((RnewevtX >= 11) && (RnewevtX <= larmap+11))
		{
		if (RnewevtX > RevtX)
			{
			widthrect = RnewevtX - RevtX;
			}	
		else
			{
			objrectevt.setAttributeNS(null,'x',RnewevtX);
			widthrect = - (RnewevtX - RevtX);
			}
		}
	else
		{
		if (RnewevtX < 11)
			{
			objrectevt.setAttributeNS(null,'x',RnewevtX);
			widthrect = -(11-RevtX);
			} 
		if (RnewevtX > larmap+11)
			{
			widthrect = larmap+11-RevtX;
			}
		}
	if ((RnewevtY> 11 ) && (RnewevtY <= 11 + haumap))
		{	
		if (RnewevtY > RevtY)
			{
			heightrect = RnewevtY - RevtY;
			}
		else
			{
			objrectevt.setAttributeNS(null,'y',RnewevtY);
			heightrect = -(RnewevtY - RevtY);
			}
		}
	else
		{
		if (RnewevtY<= 11 )
			{
			objrectevt.setAttributeNS(null,'y',RnewevtY);
			heightrect = -(11 - RevtY);
			}
		if (RnewevtY > 11 + haumap)	
			{
			heightrect = haumap+11 - RevtY;
			}
		}
	objrectevt.setAttributeNS(null,'height',heightrect);
	objrectevt.setAttributeNS(null,'width',widthrect);
	objrectevt.getStyle().setProperty('visibility','visible');
	}
}

function endResize(evt)
{
	if ((Rpressed == 1) && (zoomtool == 'zoomin'))
	{
	objrectevt = svgdoc.getElementById('rectevt');
	objrectevt.getStyle().setProperty('visibility','hidden');
	rectOvewidth = objrectevt.getAttributeNS(null,'width');
	realzoom = parseFloat((larmap/rectOvewidth)*(zoomVal));
	
	if (realzoom >= zoommax ) 
		{
		zmax=zoommin+parseFloat(intervale*18)
		Zoomto(evt,zoommax);
		}
		else
		{
	var debut=zoommin;
	var debut1=debut+intervale;
		
		for (i=0;i<17;i++)
		{
			
			if (realzoom < debut1 && realzoom >= debut) 
			{
			
			Zoomto(evt,debut1);
			}
			debut=debut+(intervale);
			debut1=debut1+(intervale);
		}
		}
		objbgrectevt = svgdoc.getElementById('bgrectevt');
		objbgrectevt.getStyle().setProperty('pointer-events','none');
		objbgrectevt.getStyle().setProperty('visibility','hidden');
		svgdoc.getElementById('releasezoom').getStyle().setProperty('visibility','hidden');
	svgdoc.getElementById('releasezoom').getStyle().setProperty('pointer-events','none');
		svgdoc.getElementById('loupe').getStyle().setProperty('opacity','1');
	svgdoc.getElementById('loupe').getStyle().setProperty('pointer-events','visible');
	zoomtool='';
	}
Rpressed = 0;
}
function sur2(evt,param1,valeur,param2,valeur2)
 {
	 idparam1="";idparam2="";retparam1="";retparam2="";
	 
	 if(ini_use==0)
	{
		node = evt.getTarget();
	 ident = node.getAttribute("rel");
	 if(svgdoc.getElementById(ident))
	 {
	 if(param1!="")
	 {
	idparam1=param1;
	retparam1=svgdoc.getElementById(ident).getAttribute(param1);
	if(retparam1=="")
	{
		retparam1="none"
	}
	 svgdoc.getElementById(ident).setAttribute(param1,valeur);
	 }
	 if(param2!="")
	 {
	idparam2=param2;	 
	 retparam2=svgdoc.getElementById(ident).getAttribute(param2);
	 if(retparam2=="")
	{
		retparam2="none"
	}
	 svgdoc.getElementById(ident).setAttribute(param2,valeur2);
	 }
	 }
	}
 }
function hors2(evt)
{

if(ini_use==0)
	{
		node = evt.getTarget();
ident = node.getAttribute("rel");
if(svgdoc.getElementById(ident))
{
if(svgdoc.getElementById(ident).getAttribute("fill")!='rgb(150,254,150)')
{
	if(retparam1!="")
	{
svgdoc.getElementById(ident).setAttribute(idparam1,retparam1);
	}
	if(retparam2!="")
	{
svgdoc.getElementById(ident).setAttribute(idparam2,retparam2);
	}
}
}
hideinfotip(evt)
	}
}
function inib_use()
{
	ini_use=1;
}
function desinib_use()
{
	ini_use=0;
}
function sur(evt,couleur,inf1,inf2)
 {
	var testcouleur=couleur;
	node = evt.getTarget();
	info="";
	if(ini_use==0)
	{
if(inf1)
{	
	if(multi=="1")
	{
		var spling="";
var reg112=new RegExp("[$]+", "g");
spling = inf2.split(reg112);
//info="";
var nbsp = spling.length;
for (k=0;k<=nbsp-1;k++)
{
		
			info=info+'<rgb>rgb(0,0,255)</rgb><i>'+spling[k]+'\n ';
		
}
info=info.substring(0,info.length-1);
	}
	else
	{
		var spling="";
var reg112=new RegExp("[$]+", "g");
spling = inf1.split(reg112);
//info="";
var nbsp = spling.length;
for (k=0;k<=nbsp-1;k++)
{
		info=info+'<rgb>rgb(255,0,0)</rgb><i>'+spling[k]+'\n ';
}
	info=info.substring(0,info.length-1);
	}
}
	coulfill=evt.currentTarget.style.getPropertyValue("fill");
	opacityfill=evt.currentTarget.style.getPropertyValue("fill-opacity");
	if(coulfill!="")
	{
	var cher=new RegExp("[rgb(]", "g");
	var cher1=new RegExp("[)]", "g");
	coulfil=coulfill.replace(cher,"");
	coulfil=coulfil.replace(cher1,"");
	var exp=new RegExp("[,]","g");
	var tabcoul=coulfil.split(exp);
		if(tabcoul[0]>200)
	{
		tabcoul[0]=parseFloat(tabcoul[0])-200;
	}
	else
	{
		tabcoul[0]=parseFloat(tabcoul[0])+56;
	}

	coulfil="rgb(";
	for (var i=0;i<tabcoul.length;i++)
{
coulfil+=tabcoul[i]+",";	
}
coulfil=coulfil.substr(0,coulfil.length-1);
coulfil+=")";
	}
	else
	{
		coulfil="rgb(255,0,0)"
	}
	
	if(node.getAttribute("fill")!='rgb(150,254,150)' && testcouleur=='none')
{
node.setAttribute("fill",coulfil);
}
if(noactive!=1)
{
if(testcouleur!='none' && node.getAttribute("fill")!='rgb(150,254,150)')
{
	node.setAttribute("fill",couleur);
	node.setAttribute("fill-opacity","0.5");
}
if(info!='')
{
	showinfotip(evt,node.getAttribute("n")+" $ "+info)
}
else
{
showinfotip(evt,node.getAttribute("n"))
}}}
}
function hors(evt)
{
node = evt.getTarget();
if(ini_use==0)
{
if(node.getAttribute("fill")!='rgb(150,254,150)')
{
node.setAttribute("fill",coulfill);
node.setAttribute("fill-opacity",opacityfill);
}
hideinfotip(evt)
}
}
function lien(evt,te,lhref,sel,nombre,target)
	{
	n_url=lhref;
	if(multi=="1")
	{
		if(sel=="m")
			{
				if(tee=="")
					{
					tee=te;
					}
				if(tee==te)
					{
					clic="1";
					ur = evt.getTarget();
					urelle = ur.getAttribute("id"); 
					if(selectio=="")
						{
						svgdoc.getElementById('valmultiple').getStyle().setProperty('visibility','visible');
						id1=urelle;
						evt.getTarget().setAttribute("fill","rgb(150,254,150)");
						evt.getTarget().setAttribute("fill-opacity","0.7");
						selectio=urelle;
						parce="'"+urelle+"'";
						}
						else
						{
						var reg=new RegExp(",", "g");
						var longueur=selectio.split(reg);
						var nb = longueur.length;
						if(nombre!=""&&nb==nombre)
							{
							message("Ce type d'objet est limite a "+nb+" selections.");
							}
							else
							{
							evt.getTarget().setAttribute("fill","rgb(150,254,150)");
							evt.getTarget().setAttribute("fill-opacity","0.7");
							id1=id1+";"+urelle;
							selectio=selectio+","+urelle;
							parce=parce+",'"+urelle+"'";
							}
						}
	
					}
				else
					{
					message("Vous devez choisir le meme type d'objet");
					}
			}
			else
				{
					message("Objet disponible uniquement en mode selection unique");
				}
	}
	else
	{
	clic="0";
	ur = evt.getTarget();
	urelle = ur.getAttribute("id"); 
	var zer="li"+urelle;
var url=n_url+urelle;
var param="_blank";
ouvrepage(url,param,zer)	}
}

function ouvrepage(url,param,zer)
{
	if(nav=="0" && param!="")
	{
		open(url,'nom')
	}
	else
	{
		var tyu=svgdoc.getElementById(zer);
		tyu.setAttributeNS('http://www.w3.org/2000/xlink/namespace/','xlink:href',url);
		if(param!="")
		{
			tyu.setAttribute('target',param);
		}
	}
}
function selectunique()
{
	if(cotation!=0 || distance!=0 || surface!=0)
	{
	bouton_distance=0	
	traitpret=0	
	noactive=0;
	clearobj()
	cotation=0
	distance=0
	surface=0
	lx=0
	svgdoc.getElementById("dessin").setAttributeNS(null,"pointer-events","visible");
	svgdoc.getElementById("desrect").setAttributeNS(null,"pointer-events","none");
	}
	else
	{
	if(multi=="0")
	{
	multi="1";
	svgdoc.getElementById("boutonselect").setAttributeNS(null,"onmouseover","showinfotip(evt,'Selection multiple')");
	//hideinfotip(evt)
	//showinfotip(evt,'Selection multiple')
	svgdoc.getElementById("sel").firstChild.data="h";
	svgdoc.getElementById("sel").setAttributeNS(null,"x",677);
	svgdoc.getElementById("sel").setAttributeNS(null,"y",232);
	}
	else
	{
	multi="0";
	svgdoc.getElementById("boutonselect").setAttribute("onmouseover","showinfotip(evt,'Selection simple')");
	//hideinfotip(evt)
	//showinfotip(evt,'Selection simple')
	svgdoc.getElementById('valmultiple').getStyle().setProperty('visibility','hidden');
	svgdoc.getElementById("sel").firstChild.data="g";
	svgdoc.getElementById("sel").setAttributeNS(null,"x",678.5);
	svgdoc.getElementById("sel").setAttributeNS(null,"y",234);
	}

selectio="";	
if (id1!="")
	{
		effaceid1()
	}
typebascule=new Array();
coulbascule=new Array();
}
}
function effaceid1()
{
	var reg=new RegExp(";", "g");


var tabla=id1.split(reg);	
var nb = tabla.length - 1;

for (var klayer = 0;klayer <= nb; klayer++)
{
	node=svgdoc.getElementById(tabla[klayer]).parentNode.getAttribute("id");
	node1=svgdoc.getElementById(node).parentNode.getAttribute("id");
	couleur=svgdoc.getElementById(node1).getAttribute("fill")
	opacite=svgdoc.getElementById(node1).getAttribute("fill-opacity")
	strok=svgdoc.getElementById(node1).getAttribute("stroke")
	svgdoc.getElementById(tabla[klayer]).setAttribute("fill",couleur);
	svgdoc.getElementById(tabla[klayer]).setAttribute("fill-opacity",opacite);
	svgdoc.getElementById(tabla[klayer]).setAttribute("stroke",strok);
}
selectio="";
tee="";
id1="";
parce="";
}
function validmulti()
{
svgdoc.getElementById('valmultiple').getStyle().setProperty('visibility','hidden');	
var zer="livalide";
var url=n_url+selectio;
var param="_blank";
ouvrepage(url,param,zer)
effaceid1()
}
function clignotement(evt,x)
{ 
if(svgdoc.getElementById(x).style.getPropertyValue('visibility')=='visible')
{
	svgdoc.getElementById(x).getStyle().setProperty('visibility','hidden')
	}
else
{

	svgdoc.getElementById(x).getStyle().setProperty('visibility','visible')

}
} 
function entre_dim(evt,cible)
{
tape_autorise=1;
cible_txt=cible
if(cible!="r_requete_thematique")
{
svgdoc.getElementById(cible).getFirstChild().setData("");
phrase="";
}
else
{
phrase=	svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()
}
}
function tape(evt)
{

if(tape_autorise==1)
{
key = evt.getCharCode();	
if ((key==8)&&(phrase!="")) 
{
phrase=phrase.substring(0,phrase.length-1)
}
else
{
lettre = String.fromCharCode(key);
if (phrase.length<256) 
{
phrase=phrase+lettre;
}
}
svgdoc.getElementById(cible_txt).getFirstChild().setData(phrase);
	
}
if(cible_txt=='r_size')
	{
		svgdoc.getElementById('symbo').setAttributeNS(null,'font-size',phrase);
		var tt=(75-parseInt(phrase))/2+parseInt(phrase)+100;
		//alert(tt)
		svgdoc.getElementById('symbo').setAttributeNS(null,'y',tt);
	}
	if(cible_txt=="r_ecri")
	   {
		   //var r="rgb(255,0,255)";
	svgdoc.getElementById('symbo').setAttributeNS(null,'fill',"rgb("+phrase+","+comp_vert+","+comp_bleu+")");
	//svgdoc.getElementById('symbo').setAttributeNS(null,'onmouseout',"switchColor(evt,'fill','rgb("+phrase+","+comp_vert+","+comp_bleu+")','','')")
	comp_rouge=phrase;
	//alert(r)
	   }
	if(cible_txt=="g_ecri")
	   {
	svgdoc.getElementById('symbo').setAttributeNS(null,'fill',"rgb("+comp_rouge+","+phrase+","+comp_bleu+")");
	//svgdoc.getElementById('symbo').setAttributeNS(null,'onmouseout',"switchColor(evt,'fill','rgb("+comp_rouge+","+phrase+","+comp_bleu+")','','')")
	comp_vert=phrase;
	   }
	if(cible_txt=="b_ecri")
	   {
	svgdoc.getElementById('symbo').setAttributeNS(null,'fill',"rgb("+comp_rouge+","+comp_vert+","+phrase+")");
	//svgdoc.getElementById('symbo').setAttributeNS(null,'onmouseout',"switchColor(evt,'fill','rgb("+comp_rouge+","+comp_vert+","+phrase+")','','')")
	comp_bleu=phrase;
	   } 
	   if(cible_txt=="opa_ecri")
	   {
	svgdoc.getElementById("symbo").setAttributeNS(null,'opacity',phrase);
	opacite=phrase;
	   }
}

function hideAbout()
{
about = svgdoc.getElementById('inforecherche');
about.getStyle().setProperty('visibility','hidden');
svgdoc.getElementById("numer").setAttribute("visibility","hidden");
}



function message(data)
{
hideinfotip(evt)
var chaine=new Array;
nbcaract=data.length;
nbligne=Math.ceil(nbcaract/55);
debu=0
for(a=0;a<nbligne;a++) 
{
	if(debu+55>nbcaract)
	{
	chaine.push(data.substring(debu));
	}
	else
	{
	chainepro=data.substr(debu,55);
	positi=chainepro.lastIndexOf(" ");
	chaine.push(data.substr(debu,positi));
	debu=debu+positi+1;
	}
}
vy=105;
for(a=0;a<chaine.length;a++) 
{
texte=svgDocument.createElementNS(null, 'text');
texte.setAttribute("pointer-events","none");
texte.setAttribute("text-anchor","middle");
texte.setAttribute("id","idmess"+a)
texte.setAttribute("font-size","10");
texte.setAttribute("x","316");
texte.setAttribute("y",vy);
text_content = svgDocument.createTextNode(chaine[a])
texte.appendChild(text_content)
svgDocument.getElementById("idmessage").appendChild(texte)
vy=vy+15;
heightcont=115;
yrectferm=155;
yidok=167.5;
if(a>2)
{
	svgdoc.getElementById('fermealerte').setAttributeNS(null,'y',yrectferm);
	svgdoc.getElementById('idcont').setAttributeNS(null,"height",heightcont);
	svgdoc.getElementById('idok').setAttributeNS(null,'y',yidok);
	heightcont=heightcont+15;
	yidok=yidok+15;
	yrectferm=yrectferm+15;
}
}
svgdoc.getElementById('message').getStyle().setProperty('visibility','visible');
//svgdoc.getElementById("idmess").getFirstChild().setData(chaine[0]);
}
function hidealert()
{
svgdoc.getElementById('message').getStyle().setProperty('visibility','hidden');
svgdoc.getElementById('fermealerte').setAttributeNS(null,'y',"140");
	svgdoc.getElementById('idcont').setAttributeNS(null,"height","100");
	svgdoc.getElementById('idok').setAttributeNS(null,'y',"152.5");
}

function choisir(evt)
{cible=evt.getTarget().getAttribute("id");
if ((cible=="rouge")||(cible=="vert")||(cible=="bleu")||(cible=="opaque"))
{appui=true}}

function bouger(evt)
{if (appui==true)
{xcurs=(evt.getClientX()-offsetXmap)/ratio;
if (xcurs<200) {xcurs=200};if (xcurs>360) {xcurs=360};
svgdoc=evt.getTarget().getOwnerDocument();obj=svgdoc.getElementById(cible);
obj.setAttribute("x",xcurs);
if (cible=="opaque") {opacite=Math.round((xcurs-200)*100/160)/100;
obj=svgdoc.getElementById("test");obj.getStyle().setProperty("fill-opacity",opacite.toString(10))} 
else 
{compo=parseInt((xcurs-200)*255/160);
if (cible=="rouge") {comp_rouge=compo};
if (cible=="vert") {comp_vert=compo};
if (cible=="bleu") {comp_bleu=compo};
obj=svgdoc.getElementById("test");obj.getStyle().setProperty("fill","rgb("+comp_rouge+","+comp_vert+","+comp_bleu+")");
}}}

function lacher(evt)
{appui=false}

function affiche_panneau_couleur(evt,type_style,ty)
{
if(type_style=='fill')
{
	type=type_style;
	if(ty=='')
{
comp_rouge=svgdoc.getElementById("r_fill").getFirstChild().getData()
comp_vert=svgdoc.getElementById("g_fill").getFirstChild().getData()
comp_bleu=svgdoc.getElementById("b_fill").getFirstChild().getData()
opacite=svgdoc.getElementById("opa").getFirstChild().getData()
}
else
{
	type=ty;
	comp_rouge=svgdoc.getElementById("r_ecri").getFirstChild().getData()
comp_vert=svgdoc.getElementById("g_ecri").getFirstChild().getData()
comp_bleu=svgdoc.getElementById("b_ecri").getFirstChild().getData()
opacite=svgdoc.getElementById("opa_ecri").getFirstChild().getData()
}
svgdoc.getElementById("opaque").setAttribute("x",((opacite*160)+200))
svgdoc.getElementById("control_opa").getStyle().setProperty('visibility','visible');
svgdoc.getElementById("test").getStyle().setProperty("fill-opacity",opacite);
}
else
{
	type=ty;
	if(ty=='')
	{
type=type_style;
	}
comp_rouge=svgdoc.getElementById("r_stroke").getFirstChild().getData()
comp_vert=svgdoc.getElementById("g_stroke").getFirstChild().getData()
comp_bleu=svgdoc.getElementById("b_stroke").getFirstChild().getData()	
svgdoc.getElementById("test").getStyle().setProperty("fill-opacity","1");
svgdoc.getElementById("control_opa").getStyle().setProperty('visibility','hidden');
}
svgdoc.getElementById("rouge").setAttribute("x",((comp_rouge*160/255)+200))
svgdoc.getElementById("vert").setAttribute("x",((comp_vert*160/255)+200))
svgdoc.getElementById("bleu").setAttribute("x",((comp_bleu*160/255)+200))
svgdoc.getElementById("test").getStyle().setProperty("fill","rgb("+ comp_rouge +","+ comp_vert +","+ comp_bleu +")");
svgdoc.getElementById("panneau_couleur").getStyle().setProperty('visibility','visible');
}
function ferme_fenetre(evt,cible)
{
svgdoc.getElementById(cible).getStyle().setProperty('visibility','hidden');
svgdoc.getElementById("remplissage").getStyle().setProperty('visibility','hidden');
svgdoc.getElementById("contoure").getStyle().setProperty('visibility','hidden');
svgdoc.getElementById("control_opa").getStyle().setProperty('visibility','hidden');
svgdoc.getElementById('deroul').style.setProperty ('visibility', 'hidden');
if(cible=='requete_thematique')
{
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData("");	
}
if(cible=='gestion_modif_couche')
{
svgdoc.getElementById('unique_v').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('select').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('princip').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('partiel').style.setProperty ('visibility', 'hidden')	
svgdoc.getElementById('initial').style.setProperty ('visibility', 'hidden')
}
if(cible=='gestion_creation_theme')
{
svgdoc.getElementById('crea_initial').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('crea_partiel').style.setProperty ('visibility', 'hidden')
}
if(cible=='gestion_mod_theme')
{
svgdoc.getElementById('mod_initial').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('mod_partiel').style.setProperty ('visibility', 'hidden')
}
if(cible=='script_mouseclick')
{
svgdoc.getElementById('securise').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('multip').style.setProperty ('visibility', 'hidden')
}
if(cible=='gestion_thematique')
{
svgdoc.getElementById('fourchette').style.setProperty ('visibility', 'hidden')	
svgdoc.getElementById('fixe').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById ("r_requete").getFirstChild().setData(" ");
svgdoc.getElementById ("r_applique").getFirstChild().setData(" ");
}
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("tderoul").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("rect_de1").setAttributeNS(null,'visibility','hidden');
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'visibility','hidden');
//svgdoc.getElementById("scroll_cursor_deroul").setAttribute("height",200);
}

function affiche_gestion(evt,rgbfill,opa,rgbstroke,widthstroke,tab_style,fontsiz,symb,ident,numcontrol,idth)
{
	url="verif_geometrie.php?idtheme="+idth;
	getURL(url,retour_geom)
	idthe=idth;
	numero_control=numcontrol;
	ident_style=ident;
	tabl_style=tab_style;
	var reg=new RegExp("[,]+", "g");
var tableau=rgbfill.split(reg);
var tableau1=rgbstroke.split(reg);
if(symb=='' && fontsiz=='')
{
if(rgbfill!="" && rgbfill!="none")
{
	remplissage='true';
svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty('opacity','1');	
svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty("pointer-events","visible");
svgdoc.getElementById("remplissage").getStyle().setProperty('visibility','hidden');
svgdoc.getElementById ("r_fill").getFirstChild().setData(tableau[0]);
svgdoc.getElementById ("g_fill").getFirstChild().setData(tableau[1]);
svgdoc.getElementById ("b_fill").getFirstChild().setData(tableau[2]);
if(opa=="")
{
	opa=1;
}
svgdoc.getElementById ("opa").getFirstChild().setData(opa);
}
else
{
	remplissage='false';
	svgdoc.getElementById ("r_fill").getFirstChild().setData("");
svgdoc.getElementById ("g_fill").getFirstChild().setData("");
svgdoc.getElementById ("b_fill").getFirstChild().setData("");
svgdoc.getElementById ("opa").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty('opacity','0.3');
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty("pointer-events","none");
	svgdoc.getElementById("remplissage").getStyle().setProperty('visibility','visible');
}
if(rgbstroke!="" && rgbstroke!="none")
{
	contour='true';
svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty('opacity','1');
svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty("pointer-events","visible");
svgdoc.getElementById("contoure").getStyle().setProperty('visibility','hidden');
svgdoc.getElementById ("r_stroke").getFirstChild().setData(tableau1[0]);
svgdoc.getElementById ("g_stroke").getFirstChild().setData(tableau1[1]);
svgdoc.getElementById ("b_stroke").getFirstChild().setData(tableau1[2]);
if(widthstroke=="")
{
	widthstroke=1;
}
svgdoc.getElementById ("larg_stroke").getFirstChild().setData(widthstroke);

}
else
{
	contour='false';
	svgdoc.getElementById ("r_stroke").getFirstChild().setData("");
svgdoc.getElementById ("g_stroke").getFirstChild().setData("");
svgdoc.getElementById ("b_stroke").getFirstChild().setData("");
svgdoc.getElementById ("larg_stroke").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty('opacity','0.3');
	svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty("pointer-events","none");
	svgdoc.getElementById("contoure").getStyle().setProperty('visibility','visible');
}


	svgdoc.getElementById("gestion_style").getStyle().setProperty('visibility','visible');
}
else
{
svgdoc.getElementById ("r_ecri").getFirstChild().setData(tableau[0]);
svgdoc.getElementById ("g_ecri").getFirstChild().setData(tableau[1]);
svgdoc.getElementById ("b_ecri").getFirstChild().setData(tableau[2]);
if(opa=="")
{
	opa=1;
}
svgdoc.getElementById ("opa_ecri").getFirstChild().setData(opa);
svgdoc.getElementById ("r_size").getFirstChild().setData(fontsiz);
comp_rouge=tableau[0]
comp_vert=tableau[1]
comp_bleu=tableau[2]
	
		svgdoc.getElementById("symbo").setAttributeNS(null,'fill','rgb('+tableau[0]+','+tableau[1]+','+tableau[2]+')');
		svgdoc.getElementById("symbo").setAttributeNS(null,'opacity',opa);
		if(symb!='')
		{
		svgdoc.getElementById ("symbo").getFirstChild().setData(symb);
		svgdoc.getElementById("symbo").setAttributeNS(null,'font-family','fontsvg')
		svgdoc.getElementById ("titre_sym").getFirstChild().setData('Gestion du symbole');
		svgdoc.getElementById ("ecri_sym").getFirstChild().setData('Symbole');
		svgdoc.getElementById("symbo").setAttributeNS(null,'pointer-events','visible')
		}
		else
		{
			svgdoc.getElementById ("symbo").getFirstChild().setData('Abc');
			svgdoc.getElementById("symbo").setAttributeNS(null,'font-family','arial')
			svgdoc.getElementById ("titre_sym").getFirstChild().setData('Gestion de la police');
			svgdoc.getElementById ("ecri_sym").getFirstChild().setData('Caractere');
			svgdoc.getElementById("symbo").setAttributeNS(null,'pointer-events','none')
		}
		var tt=(75-parseInt(fontsiz))/2+parseInt(fontsiz)+100;
		svgdoc.getElementById('symbo').setAttributeNS(null,'y',tt);
		svgdoc.getElementById('symbo').setAttributeNS(null,'font-size',fontsiz);
		
svgdoc.getElementById("gestion_ecriture").getStyle().setProperty('visibility','visible');
}
}

function valide_coul(evt)
{
	if(type=="fill")
	{
	svgdoc.getElementById ("r_fill").getFirstChild().setData(comp_rouge);
	svgdoc.getElementById ("g_fill").getFirstChild().setData(comp_vert);
	svgdoc.getElementById ("b_fill").getFirstChild().setData(comp_bleu);
	svgdoc.getElementById ("opa").getFirstChild().setData(opacite);	
	}
	else if(type=="symbo")
	{
	svgdoc.getElementById ("r_ecri").getFirstChild().setData(comp_rouge);
	svgdoc.getElementById ("g_ecri").getFirstChild().setData(comp_vert);
	svgdoc.getElementById ("b_ecri").getFirstChild().setData(comp_bleu);
	svgdoc.getElementById ("opa_ecri").getFirstChild().setData(opacite);
	svgdoc.getElementById("symbo").setAttributeNS(null,'fill','rgb('+comp_rouge+','+comp_vert+','+comp_bleu+')');
	svgdoc.getElementById("symbo").setAttributeNS(null,'opacity',opacite);
	}
	else if(type=="r_survol")
	{
		svgdoc.getElementById("r_survol").getFirstChild().setData("rgb("+comp_rouge+","+comp_vert+","+comp_bleu+")");
		//svgdoc.getElementById("r_mouseout").getFirstChild().setData("hors(evt)");
	}
	else
	{
	svgdoc.getElementById ("r_stroke").getFirstChild().setData(comp_rouge);
	svgdoc.getElementById ("g_stroke").getFirstChild().setData(comp_vert);
	svgdoc.getElementById ("b_stroke").getFirstChild().setData(comp_bleu);
	}
	svgdoc.getElementById("panneau_couleur").getStyle().setProperty('visibility','hidden');
	svgdoc.getElementById("control_opa").getStyle().setProperty('visibility','hidden');
}
function no(evt,cible)
{
	if(cible=='contour' && bascule_contour==0)
	{
		contour='false';
		bascule_contour=1;
	svgdoc.getElementById ("r_stroke").getFirstChild().setData("");
svgdoc.getElementById ("g_stroke").getFirstChild().setData("");
svgdoc.getElementById ("b_stroke").getFirstChild().setData("");
svgdoc.getElementById ("larg_stroke").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty('opacity','0.3');
	svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty("pointer-events","none");
	svgdoc.getElementById("contoure").getStyle().setProperty('visibility','visible');
	}
	else if(cible=='contour' && bascule_contour==1)
	{
		contour='true';
		bascule_contour=0;
		svgdoc.getElementById ("r_stroke").getFirstChild().setData("0");
svgdoc.getElementById ("g_stroke").getFirstChild().setData("0");
svgdoc.getElementById ("b_stroke").getFirstChild().setData("0");
svgdoc.getElementById ("larg_stroke").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty('opacity','1');
	svgdoc.getElementById("gestion_coul_contour").getStyle().setProperty("pointer-events","visible");
	svgdoc.getElementById("contoure").getStyle().setProperty('visibility','hidden');
	}
	else if((cible=='remplissage' && bascule_fond==0) || geom_type=='line')
	{
		remplissage='false';
		bascule_fond=1;
		svgdoc.getElementById ("r_fill").getFirstChild().setData("");
svgdoc.getElementById ("g_fill").getFirstChild().setData("");
svgdoc.getElementById ("b_fill").getFirstChild().setData("");
svgdoc.getElementById ("opa").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty('opacity','0.3');
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty("pointer-events","none");
	svgdoc.getElementById("remplissage").getStyle().setProperty('visibility','visible');
	if(geom_type=='line')
	{
		message("C'est une geometrie de type 'line', on ne peut pas affecter un replissage a ce type d'objet")
	}
	}
	else
	{
		remplissage='true';
		bascule_fond=0;
		svgdoc.getElementById ("r_fill").getFirstChild().setData("0");
svgdoc.getElementById ("g_fill").getFirstChild().setData("0");
svgdoc.getElementById ("b_fill").getFirstChild().setData("0");
svgdoc.getElementById ("opa").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty('opacity','1');
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty("pointer-events","visible");
	svgdoc.getElementById("remplissage").getStyle().setProperty('visibility','hidden');
	}
	
}
function valide_style(evt)
{
	
	if(tabl_style=='style')
	{
		tabl='style';
		clause_style="idstyle='"+ident_style+"'";
		res=svgdoc.getElementById("coche"+numero_control).getAttribute("onclick")
	res=res.substr(9,res.length-1);
	var reg=new RegExp("[,]+", "g");
	var tableau=res.split(reg);
	couchesvgvisible=tableau[0].substr(0,tableau[0].length-1)
	}
	else
	{
		tabl='col_theme';
		var reg=new RegExp("[.]+", "g");
var tableau=ident_style.split(reg);
		clause_style="idappthe='"+tableau[0]+"' and intitule_legende='"+tableau[1]+"'";
		couchesvgvisible=ident_style;
	}
	requete='UPDATE admin_svg.'+tabl+' SET ';
	if(remplissage=='true')
	{
		r_fill=svgdoc.getElementById("r_fill").getFirstChild().getData()
		g_fill=svgdoc.getElementById("g_fill").getFirstChild().getData()
		b_fill=svgdoc.getElementById("b_fill").getFirstChild().getData()
		opa=svgdoc.getElementById("opa").getFirstChild().getData()
	requete+="fill='"+r_fill+","+g_fill+","+b_fill+"',opacity='"+opa+"',";
	fi=r_fill+","+g_fill+","+b_fill;
	}
	else
	{
		requete+="fill='none',opacity='1',";
		fi="none";
		opa=1;
	}
	if(contour=='true')
	{
		r_stroke=svgdoc.getElementById("r_stroke").getFirstChild().getData()
		g_stroke=svgdoc.getElementById("g_stroke").getFirstChild().getData()
		b_stroke=svgdoc.getElementById("b_stroke").getFirstChild().getData()
		larg_stroke=svgdoc.getElementById("larg_stroke").getFirstChild().getData()
	requete+="stroke_rgb='"+r_stroke+","+g_stroke+","+b_stroke+"',stroke_width='"+larg_stroke+"',";
	st=r_stroke+","+g_stroke+","+b_stroke;
	}
	else
	{
		requete+="stroke_rgb='none',stroke_width='1',";
		st="none";
		larg_stroke=1;
	}
	requete=requete.substr(0,requete.length-1);
	requete+=" where "+clause_style;
	url="execute_sql.php?requete="+requete+"&genere=true";
	
	ferme_fenetre(evt,'gestion_style')
	
	getURL(url,null)
	if(remplissage=='true')
	{
		couleu="rgb("+r_fill+","+g_fill+","+b_fill+")";
	}
	else if(contour=='true')
	{
		couleu="rgb("+r_stroke+","+g_stroke+","+b_stroke+")";
	}
	else
	{
		couleu="rgb(0,0,0)";
	}
	svgdoc.getElementById("coul"+numero_control).getStyle().setProperty("fill",couleu);
	svgdoc.getElementById("coul"+numero_control).getStyle().setProperty("fill-opacity",opa);
	svgdoc.getElementById("coul"+numero_control).setAttributeNS(null,"onclick","affiche_gestion(evt,'"+fi+"','"+opa+"','"+st+"','"+larg_stroke+"','"+tabl_style+"','','','"+ident_style+"','"+numero_control+"')")
	
	clear('control'+numero_control)
	
	extraction('control'+numero_control)
		
	
}

function retour_geom(data)
{
	 var string = '';
    if(data.success) 
	{
		var chaine=data.content;
		
var reg1=new RegExp("LINE","g");
if (chaine.match(reg1)) {
	
 remplissage='false';
 geom_type="line";
	svgdoc.getElementById ("r_fill").getFirstChild().setData("");
svgdoc.getElementById ("g_fill").getFirstChild().setData("");
svgdoc.getElementById ("b_fill").getFirstChild().setData("");
svgdoc.getElementById ("opa").getFirstChild().setData("1");
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty('opacity','0.3');
	svgdoc.getElementById("gestion_coul_fond").getStyle().setProperty("pointer-events","none");
	svgdoc.getElementById("remplissage").getStyle().setProperty('visibility','visible');
}
var reg2=new RegExp("POINT","g");
if (chaine.match(reg2)) 
{
	geom_type="point";
}
else
{
	geom_type="polygone";
}
	}
}
function affiche_list_symbol()
{
svgdoc.getElementById("gestion_symbol").getStyle().setProperty('visibility','visible');
}
function select_symb(symb)
{
	
svgdoc.getElementById ("symbo").getFirstChild().setData(symb);	
svgdoc.getElementById("gestion_symbol").getStyle().setProperty('visibility','hidden');
}
function valide_ecriture(evt)
{
	the_raster="flase"
	if(tabl_style=='style')
	{
		tabl='style';
		clause_style="idstyle='"+ident_style+"'";
		res=svgdoc.getElementById("coche"+numero_control).getAttribute("onclick")
	res=res.substr(9,res.length-1);
	var reg=new RegExp("[,]+", "g");
	var tableau=res.split(reg);
	var exp=new RegExp("[;]+","g");
var tabrasteur=coucherastervisible.split(exp);
for (var i=0;i<tabrasteur.length;i++)
{
	if(tabrasteur[i]==tableau[0].substr(0,tableau[0].length-1))
	{
	the_raster="true"
	}
}
	if(the_raster=="false")
	{
	couchesvgvisible=tableau[0].substr(0,tableau[0].length-1)
	}
	}
	else
	{
		tabl='col_theme';
		var reg=new RegExp("[.]+", "g");
var tableau=ident_style.split(reg);
		clause_style="idappthe='"+tableau[0]+"' and intitule_legende='"+tableau[1]+"'";
		
		var exp=new RegExp("[;]+","g");
var tabrasteur=coucherastervisible.split(exp);
for (var i=0;i<tabrasteur.length;i++)
{
	if(tabrasteur[i]==ident_style)
	{
	the_raster="true"
	}
}
	if(the_raster=="false")
	{
		couchesvgvisible=ident_style;
	}
	}
	requete='UPDATE admin_svg.'+tabl+' SET ';
	r_fill=svgdoc.getElementById("r_ecri").getFirstChild().getData()
		g_fill=svgdoc.getElementById("g_ecri").getFirstChild().getData()
		b_fill=svgdoc.getElementById("b_ecri").getFirstChild().getData()
		opa=svgdoc.getElementById("opa_ecri").getFirstChild().getData()
		sym=svgdoc.getElementById("symbo").getFirstChild().getData()
		if(sym=="Abc")
		{
			sym=""
		}
		else
		{
			svgdoc.getElementById("coul"+numero_control).getFirstChild().setData(sym);
		}
		siz=svgdoc.getElementById("r_size").getFirstChild().getData()
	requete+="fill='"+r_fill+","+g_fill+","+b_fill+"',opacity='"+opa+"',symbole='"+sym+"',id_symbole='"+sym+"',font_size='"+siz+"'";
	fi=r_fill+","+g_fill+","+b_fill;
	requete+=" where "+clause_style;
	url="execute_sql.php?requete="+requete+"&genere=true";
	ferme_fenetre(evt,'gestion_ecriture')
	getURL(url,null)
	svgdoc.getElementById("coul"+numero_control).getStyle().setProperty("fill","rgb("+fi+")");
	svgdoc.getElementById("coul"+numero_control).getStyle().setProperty("fill-opacity",opa);
	
	svgdoc.getElementById("coul"+numero_control).setAttributeNS(null,"onclick","affiche_gestion(evt,'"+fi+"','"+opa+"','','','"+tabl_style+"','"+siz+"','"+sym+"','"+ident_style+"','"+numero_control+"')")
	
	if(the_raster=="false")
	{
	clear('control'+numero_control)
	extraction('control'+numero_control)
	}
	else
	{
		if(svgdoc.getElementById("tra"+numero_control).getStyle().getPropertyValue("visibility") == "visible")
		{
		clear('controlraster')
		extraction('controlraster');
		}
	}
}
function up_txt_legende(evt)
{
	if(posi_txt_legende!=1)
	{
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('tri_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"pointer-events","visible")
	}
	res=posilegend[(parseInt(posi_txt_legende)-1)]
	y1=svgdoc.getElementById(res).getAttributeNS(null,"y")
	//alert(y1)
	yy=parseInt(y1)+15;
	//alert(yy)
	svgdoc.getElementById(res).setAttributeNS(null,"y",yy)
	svgdoc.getElementById(posilegend[(posi_txt_legende)]).setAttributeNS(null,"y",y1)
	svgdoc.getElementById(posilegend[(parseInt(posi_txt_legende)-1)]).setAttributeNS(null,"onclick","sel_txt_legende(evt,"+posi_txt_legende+")")
	svgdoc.getElementById(posilegend[(posi_txt_legende)]).setAttributeNS(null,"onclick","sel_txt_legende(evt,"+(parseInt(posi_txt_legende)-1)+")")
	posilegend[(parseInt(posi_txt_legende)-1)]=posilegend[posi_txt_legende];
	posilegend[posi_txt_legende]=res
	posi_txt_legende=posi_txt_legende-1
	if(posi_txt_legende==1)
	{
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('tri_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"pointer-events","none")
	}
	nbval=posilegend.length;
  if(posi_txt_legende!=nbval-1)
	{
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('tri1_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"pointer-events","visible")
	}
	
}
function down_txt_legende(evt)
{
  nbval=posilegend.length;
  if(posi_txt_legende!=nbval-1)
	{
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('tri1_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"pointer-events","visible")
	}
  res=posilegend[(parseInt(posi_txt_legende)+1)]
  //alert(posi_txt_legende+1)
	y1=svgdoc.getElementById(res).getAttributeNS(null,"y")
	//alert(y1)
	yy=parseInt(y1)-15;
	//alert(yy)
	svgdoc.getElementById(res).setAttributeNS(null,"y",yy)
	svgdoc.getElementById(posilegend[(posi_txt_legende)]).setAttributeNS(null,"y",y1)
	svgdoc.getElementById(posilegend[(parseInt(posi_txt_legende)+1)]).setAttributeNS(null,"onclick","sel_txt_legende(evt,"+posi_txt_legende+")")
	svgdoc.getElementById(posilegend[(posi_txt_legende)]).setAttributeNS(null,"onclick","sel_txt_legende(evt,"+(parseInt(posi_txt_legende)+1)+")")
	posilegend[(parseInt(posi_txt_legende)+1)]=posilegend[posi_txt_legende];
	posilegend[posi_txt_legende]=res
	posi_txt_legende=(parseInt(posi_txt_legende)+1)  
	if(posi_txt_legende==nbval-1)
	{
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('tri1_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"pointer-events","none")
	}
	if(posi_txt_legende!=1)
	{
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('tri_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"pointer-events","visible")
	}	
 }
 function sel_txt_legende(evt,z)
 {
	if(appui_txt_legende!="false")
	{
		svgdoc.getElementById(appui_txt_legende).setAttributeNS(null,"text-decoration",'')
	}
	appui_txt_legende=evt.target.getAttributeNS(null , "id");
	svgdoc.getElementById(appui_txt_legende).setAttributeNS(null,"text-decoration",'underline')
	posi_txt_legende=z;
	if(posi_txt_legende==1)
	{
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('tri_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"pointer-events","none")
	}	
	if(posi_txt_legende!=1)
	{
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('tri_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('up_txt_legende').setAttributeNS(null,"pointer-events","visible")
	}	
	nbval=posilegend.length;
  if(posi_txt_legende!=nbval-1)
	{
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('tri1_txt_legende').setAttributeNS(null,"opacity","1")
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"pointer-events","visible")
	}
	 if(posi_txt_legende==nbval-1)
	{
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('tri1_txt_legende').setAttributeNS(null,"opacity","0.5")
		svgdoc.getElementById('down_txt_legende').setAttributeNS(null,"pointer-events","none")
	}
 }
 function valide_ordre(evt)
 {
	valeu=''
	var exp=new RegExp("[.]+","g");
	 for (var i=1;i<posilegend.length;i++)
{
	if(posilegend[i]!=posilegendini[i])
	{
	var mod_legend=posilegend[i].split(exp);
	valeu+=mod_legend[0]+"."+i+"|";
	posilegendini[i]=posilegend[i]
	}
}
ferme_fenetre(evt,'gestion_ordre')
if(valeu!='')
{
	valeu=valeu.substr(0,valeu.length-1)
	url="update_ordre_legende.php?ordre="+valeu;
		
	getURL(url,null)
	
	
		var zer="liordre";
var url='http://'+serveur+'/interface/back_office/back_office.php';
var param="";
ouvrepage(url,param,zer)
}
 }

function ouvre_mod_legend(evt)
{
	svgdoc.getElementById('gestion_ordre').getStyle().setProperty('visibility','visible');
}
function retour()
{
	var zer="liretour";
var url='http://'+serveur+'/interface/back_office/';
var param="";
ouvrepage(url,param,zer)
}
function gest_control_choix(evt,x)
{
svgdoc.getElementById('info').style.setProperty ('visibility', 'visible');	
	
svgdoc.getElementById('infote1').setAttributeNS(null,'x', (evt.getClientX()-offsetXmap)/ratio+5);
svgdoc.getElementById('infote1').setAttributeNS(null,'y', (evt.getClientY()-offsetYmap)/ratio+10);
svgdoc.getElementById('infote1').setAttributeNS(null,'onclick','modi_couche('+x+')');
svgdoc.getElementById('infote1').style.setProperty('font-size', 8);

svgdoc.getElementById('infote2').setAttributeNS(null,'x', (evt.getClientX()-offsetXmap)/ratio+5);
svgdoc.getElementById('infote2').setAttributeNS(null,'y', (evt.getClientY()-offsetYmap)/ratio+20);
svgdoc.getElementById('infote2').setAttributeNS(null,'onclick','supp_couche('+x+')');

svgdoc.getElementById('infote2').style.setProperty('font-size', 8);

svgdoc.getElementById('inforect').setAttributeNS(null,'x', (evt.getClientX()-offsetXmap)/ratio);
svgdoc.getElementById('inforect').setAttributeNS(null,'y', (evt.getClientY()-offsetYmap)/ratio);
}

function modi_couche(x)
{
	var d=new Date();
svgdoc.getElementById('info').style.setProperty ('visibility', 'hidden');
	url='propriete_theme.php?objkey=' + x +'&d=' + d.getHours()+'_'+d.getMinutes()+'_'+d.getSeconds()+'&indice=propri';
	getURL(url,retourpropriete)
}
function retourpropriete(data)
{
var fragment=data.content.split("#");
pro_over=fragment[0].split("#");
pro_click=fragment[1].split("#");
pro_out=fragment[2].split("#");
pro_vect=fragment[3].split("#");
pro_min_v=fragment[4].split("#");
pro_max_v=fragment[5].split("#");
pro_max_r=fragment[6].split("#");
pro_selec=fragment[7].split("#");
pro_princip=fragment[8].split("#");
pro_partiel=fragment[9].split("#");
pro_initial=fragment[10].split("#");
pro_ident=fragment[11].split("#");
pro_ad=fragment[12].split("#");
pro_schema=fragment[13].split("#");
pro_tabl=fragment[14].split("#");
idtheme=fragment[15].split("#");
idappthe=fragment[16].split("#");
svgdoc.getElementById ("r_mouseover").getFirstChild().setData(pro_over);
svgdoc.getElementById ("r_click").getFirstChild().setData(pro_click);
svgdoc.getElementById ("r_mouseout").getFirstChild().setData(pro_out);
svgdoc.getElementById ("r_zoom_max_v").getFirstChild().setData(pro_max_v);
svgdoc.getElementById ("r_zoom_min_v").getFirstChild().setData(pro_min_v);
svgdoc.getElementById ("r_zoom_max_rast").getFirstChild().setData(pro_max_r);
if(pro_vect=='f')
{
svgdoc.getElementById('unique_v').style.setProperty ('visibility', 'visible')	
}
else
{
svgdoc.getElementById('unique_v').style.setProperty ('visibility', 'hidden')	
}
if(pro_selec=='t')
{
svgdoc.getElementById('select').style.setProperty ('visibility', 'visible')	
}
else
{
svgdoc.getElementById('select').style.setProperty ('visibility', 'hidden')	
}
if(pro_princip=='t')
{
svgdoc.getElementById('princip').style.setProperty ('visibility', 'visible')	
}
else
{
svgdoc.getElementById('princip').style.setProperty ('visibility', 'hidden')	
}
if(pro_partiel=='1')
{
svgdoc.getElementById('partiel').style.setProperty ('visibility', 'visible')	
}
else
{
svgdoc.getElementById('partiel').style.setProperty ('visibility', 'hidden')	
}
if(pro_initial=='1')
{
svgdoc.getElementById('initial').style.setProperty ('visibility', 'visible')	
}
else
{
svgdoc.getElementById('initial').style.setProperty ('visibility', 'hidden')	
}
svgdoc.getElementById('gestion_modif_couche').style.setProperty ('visibility', 'visible');
}
function test_update_propriete_theme()
{
v_vect="false";v_selec="true";v_princip="true";verif_vect="f";verif_selec="t";verif_princip="t";verif_partiel="1";verif_initial="1";verif_click="";
verif_over=svgdoc.getElementById("r_mouseover").getFirstChild().getData();
verif_click=svgdoc.getElementById("r_click").getFirstChild().getData();
verif_out=svgdoc.getElementById("r_mouseout").getFirstChild().getData();
if (svgdoc.getElementById("unique_v").getStyle().getPropertyValue("visibility") == "hidden")
{
verif_vect="t";
v_vect='true';
}
verif_min_v=svgdoc.getElementById("r_zoom_min_v").getFirstChild().getData();
verif_max_v=svgdoc.getElementById("r_zoom_max_v").getFirstChild().getData();
verif_max_r=svgdoc.getElementById("r_zoom_max_rast").getFirstChild().getData();
if (svgdoc.getElementById("select").getStyle().getPropertyValue("visibility") == "hidden")
{
verif_selec="f";
v_selec="false";
}
if (svgdoc.getElementById("princip").getStyle().getPropertyValue("visibility") == "hidden")
{
verif_princip="f";
v_princip='false';
}
if (svgdoc.getElementById("partiel").getStyle().getPropertyValue("visibility") == "hidden")
{
verif_partiel="0";
}
if (svgdoc.getElementById("initial").getStyle().getPropertyValue("visibility") == "hidden")
{
verif_initial="0";
}
if(verif_over==pro_over && verif_click==pro_click && verif_out==pro_out && verif_vect==pro_vect && verif_min_v==pro_min_v && verif_max_v==pro_max_v && verif_max_r==pro_max_r && verif_selec==pro_selec && verif_princip==pro_princip && verif_partiel==pro_partiel && verif_initial==pro_initial)
{
message("aucune modification")	
}
else
{

	if((pro_ad=="" || pro_ident=="") && (verif_over!="" || verif_click!=""))
	{
	alert('nook')
	ajout_ad_id()
	}
	else
	{
		if(verif_over=="" && verif_click=="" && verif_out=="")
		{
			r_event="none";
		}
		else
		{
			r_event="visible";
		}
		if(verif_min_v=="")
		{
			verif_min_v="null";
		}
		if(verif_max_v=="")
		{
			verif_max_v="null";
		}
		if(verif_max_r=="")
		{
			verif_max_r="null";
		}
		var reg=new RegExp("(:)", "g");
		verif_click=verif_click.replace(reg,"c(2p)")
		verif_over=verif_over.replace(reg,"c(2p)")
		var reg=new RegExp("[\+]", "g");
		verif_click=verif_click.replace(reg,"c(plus)")
		verif_over=verif_over.replace(reg,"c(plus)")
		var reg=new RegExp("(')", "g");
		requete="UPDATE admin_svg.appthe SET mouseover='"+verif_over.replace(reg,"''")+"',click='"+verif_click.replace(reg,"''")+"',mouseout='"+verif_out.replace(reg,"''")+"',pointer_events='"+r_event+"',raster='"+v_vect+"',zoommin="+verif_min_v+",zoommax="+verif_max_v+",zoommaxraster="+verif_max_r+",objselection='"+v_selec+"',objprincipal='"+v_princip+"',partiel='"+verif_partiel+"',vu_initial='"+verif_initial+"' where idappthe='"+idappthe+"'";
	
		getURL("./execute_sql.php?requete="+requete+"&genere=false",null)
		
		ferme_fenetre(evt,'gestion_modif_couche')
		var zer="li_pro";
var url='http://'+serveur+'/interface/back_office/back_office.php';
var param="";
ouvrepage(url,param,zer)
	}
}
}

function sousmettre_thematique()
{
if(svgdoc.getElementById("r_applique").getFirstChild().getData()=="valeur fixe")
{
getURL("./thematique.php?type=fixe&appel="+svgdoc.getElementById("r_requete").getFirstChild().getData()+"&idtheme="+idtheme,retour_thematique)
}
else if(svgdoc.getElementById("r_applique").getFirstChild().getData()=="fourchette")
{
	getURL("./thematique.php?type=fourchette&appel="+svgdoc.getElementById("r_requete").getFirstChild().getData()+"&idtheme="+idtheme,retour_thematique)
}
else
{
	message('vous devez faire un choix entre valeur fixe et fourchette')
}
}
function retour_thematique(data)
{
	idppthe="";
if(svgdoc.getElementById("r_applique").getFirstChild().getData()=="valeur fixe")
{	
	svgdoc.getElementById('fixe').style.setProperty ('visibility', 'visible');
	fragment=data.content.split('#')
	for (k=0; k<fragment.length; k++)
		{
		countvalfixe=countvalfixe+1;
		rectan=svgDocument.createElementNS(null, 'rect');
		rectan.setAttribute("class","defaut");
		rectan.setAttribute("id","rec_filtre"+k)
		rectan.setAttribute("x",216);
		rectan.setAttribute("y",202.5+10+(k*10));
		rectan.setAttribute("width",100);
		rectan.setAttribute("height",10);
		svgDocument.getElementById("valfixe").appendChild(rectan)
		texte=svgDocument.createElementNS(null, 'text');
texte.setAttribute("class","fillfonce");
texte.setAttribute("id","tex_filtre"+k)
texte.setAttribute("x",266);
texte.setAttribute("y",210+10+(k*10));
texte.setAttribute("font-size","8");
texte.setAttribute("text-anchor","middle");
text_content = svgDocument.createTextNode(fragment[k].split("#"));
texte.appendChild(text_content)
svgDocument.getElementById("valfixe").appendChild(texte)
rectan1=svgDocument.createElementNS(null, 'rect');
		rectan1.setAttribute("class","defaut");
		rectan1.setAttribute("id","rec_fixe_leg"+k)
		rectan1.setAttribute("x",316);
		rectan1.setAttribute("y",202.5+10+(k*10));
		rectan1.setAttribute("width",100);
		rectan1.setAttribute("height",10);
		rectan1.setAttribute("onclick","entre_dim(evt,'tex_fixe_leg"+k+"')")
		svgDocument.getElementById("valfixe").appendChild(rectan1)
		texte1=svgDocument.createElementNS(null, 'text');
texte1.setAttribute("class","fillfonce");
texte1.setAttribute("id","tex_fixe_leg"+k)
texte1.setAttribute("x",366);
texte1.setAttribute("y",210+10+(k*10));
texte1.setAttribute("font-size","8");
texte1.setAttribute("text-anchor","middle");
texte1.setAttribute("pointer-events","none")
text_content1 = svgDocument.createTextNode(fragment[k].split("#"));
texte1.appendChild(text_content1)
svgDocument.getElementById("valfixe").appendChild(texte1)
		}
		if(fragment.length>12)
{
svgdoc.getElementById("scroll_cursor1").setAttribute("height",120-((fragment.length-12)*2));
}
else
{
svgdoc.getElementById("valfixe").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("curseur1").setAttributeNS(null,'transform','translate(0,0)')
svgdoc.getElementById("scroll_cursor1").setAttribute("height",120);
}
}
else
{
svgdoc.getElementById('fourchette').style.setProperty ('visibility', 'visible');
fragment=data.content.split('#')
fourche1=Math.round(parseFloat(fragment[1])-(fragment[1]*30/100));
fourche2=Math.round(parseFloat(fragment[1])-(fragment[1]*15/100));
fourche4=Math.round(parseFloat(fragment[1])+parseFloat(fragment[1]*15/100));
fourche5=Math.round(parseFloat(fragment[1])+parseFloat(fragment[1]*30/100));
svgdoc.getElementById ("r_fourche0_mini").getFirstChild().setData(Math.round(fragment[0]));
svgdoc.getElementById ("r_fourche0_maxi").getFirstChild().setData(fourche1);
svgdoc.getElementById ("r_fourche0_leg").getFirstChild().setData("de "+Math.round(fragment[0])+" a "+fourche1);
svgdoc.getElementById ("r_fourche1_mini").getFirstChild().setData(fourche1+0.01);
svgdoc.getElementById ("r_fourche1_maxi").getFirstChild().setData(fourche2);
svgdoc.getElementById ("r_fourche1_leg").getFirstChild().setData("de "+fourche1+" a "+fourche2);
svgdoc.getElementById ("r_fourche2_mini").getFirstChild().setData(fourche2+0.01);
svgdoc.getElementById ("r_fourche2_maxi").getFirstChild().setData(Math.round(fragment[1]));
svgdoc.getElementById ("r_fourche2_leg").getFirstChild().setData("de "+fourche2+" a "+Math.round(fragment[1]));
svgdoc.getElementById ("r_fourche3_mini").getFirstChild().setData(Math.round(parseFloat(fragment[1])+parseFloat(0.01)));
svgdoc.getElementById ("r_fourche3_maxi").getFirstChild().setData(fourche4);
svgdoc.getElementById ("r_fourche3_leg").getFirstChild().setData("de "+Math.round(fragment[1])+" a "+fourche4);
svgdoc.getElementById ("r_fourche4_mini").getFirstChild().setData(fourche4+0.01);
svgdoc.getElementById ("r_fourche4_maxi").getFirstChild().setData(fourche5);
svgdoc.getElementById ("r_fourche4_leg").getFirstChild().setData("de "+fourche4+" a "+fourche5);
svgdoc.getElementById ("r_fourche5_mini").getFirstChild().setData(fourche5+0.01);
svgdoc.getElementById ("r_fourche5_maxi").getFirstChild().setData(fragment[2]);
svgdoc.getElementById ("r_fourche5_leg").getFirstChild().setData("supp a "+fourche5);
}
}
function termine_thematique()
{
ferme_fenetre(evt,'gestion_thematique')
		var zer="li_thematique";
var url='http://'+serveur+'/interface/back_office/back_office.php';
var param="";
ouvrepage(url,param,zer)	
}
function creer_thematique()
{
	svgdoc.getElementById('terminer_gestion_thematique').style.setProperty ('visibility', 'hidden')
		svgdoc.getElementById('txt_termine').style.setProperty ('visibility', 'hidden')
	url="update_style.php?appli="+appli+"&idtheme="+idtheme+"&genere=false";
	getURL(url,retouridappthe)
}
function creer_thematique_suite()
{
	if(svgdoc.getElementById("r_applique").getFirstChild().getData()=="valeur fixe")
	{
	ecartcouleur=Math.floor(255/countvalfixe);
	colonn=svgdoc.getElementById("r_requete").getFirstChild().getData();
	//alert(colonn+','+ecartcouleur+','+idtheme+','+appli)
	for (k=0; k<countvalfixe; k++)
		{
		legende=svgdoc.getElementById("tex_fixe_leg"+k).getFirstChild().getData();
		valtext=svgdoc.getElementById("tex_filtre"+k).getFirstChild().getData();
		couleur=(0+ecartcouleur*k)+",100,150";
		url1="ajout_col_theme.php?type=fixe&idappthe="+idappthe+"&appli="+appli+"&idtheme="+idtheme+"&couleur="+couleur+"&colonn="+colonn+"&legende="+legende+"&valtexte="+valtext+"&ordre="+(k+1);
		//alert(url)
	getURL(url1,null)
		
		}
		svgdoc.getElementById('rec_att').style.setProperty ('visibility', 'visible')
		svgdoc.getElementById('txt_att').style.setProperty ('visibility', 'visible')
		url="execute_sql.php?&genere=true";
	getURL(url,testferme)
	}
	else
	{
		countvalfixe=0
	for (k=0; k<12; k++)
		{
		if (svgdoc.getElementById("r_fourche"+k+"_mini").getFirstChild().getData()!=" " && svgdoc.getElementById("r_fourche"+k+"_mini").getFirstChild().getData()!="")
			{
			countvalfixe=countvalfixe+1;	
			}
		}
	ecartcouleur=Math.floor(255/countvalfixe);
	colonn=svgdoc.getElementById("r_requete").getFirstChild().getData();	
	for (k=0; k<countvalfixe; k++)
		{
		legende=svgdoc.getElementById("r_fourche"+k+"_leg").getFirstChild().getData();
		valmini=svgdoc.getElementById("r_fourche"+k+"_mini").getFirstChild().getData();
		valmaxi=svgdoc.getElementById("r_fourche"+k+"_maxi").getFirstChild().getData();
		couleur=(0+ecartcouleur*k)+",100,150";
		url1="ajout_col_theme.php?type=fourchette&idappthe="+idappthe+"&appli="+appli+"&idtheme="+idtheme+"&couleur="+couleur+"&colonn="+colonn+"&legende="+legende+"&valmini="+valmini+"&valmaxi="+valmaxi+"&ordre="+(k+1);
		//alert(url1)
		//alert(url)
	getURL(url1,null)
		}
	svgdoc.getElementById('rec_att').style.setProperty ('visibility', 'visible')
		svgdoc.getElementById('txt_att').style.setProperty ('visibility', 'visible')
		url="execute_sql.php?&genere=true";
	getURL(url,testferme)
	}
}

function testferme(data)
{
svgdoc.getElementById('rec_att').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('txt_att').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById('terminer_gestion_thematique').style.setProperty ('visibility', 'visible')
svgdoc.getElementById('txt_termine').style.setProperty ('visibility', 'visible')
}
function retouridappthe(data)
{
idappthe=data.content
creer_thematique_suite()
}

function ajout_ad_id()
{
svgdoc.getElementById('ajout_ad_id').style.setProperty ('visibility', 'visible');
svgdoc.getElementById ("r_ad").getFirstChild().setData(pro_ad);
svgdoc.getElementById ("r_id").getFirstChild().setData(pro_ident);
}
function appel_req(x)
{
col_geom=x;
svgdoc.getElementById('requete_thematique').style.setProperty ('visibility', 'visible')
}
function appli_req_thema()
{
svgdoc.getElementById (col_geom).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData());
svgdoc.getElementById('requete_thematique').style.setProperty ('visibility', 'hidden')
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData(" ")
}
function surface_thema()
{
bouton_thematique="surface";
appel_deroulant(evt,"r_requete_thematique")
}
function longueur_thema()
{
bouton_thematique="longueur";
appel_deroulant(evt,"r_requete_thematique")
}
function souschaine_thema()
{
bouton_thematique="souschaine";
appel_deroulant(evt,"r_requete_thematique")
}
function x_thema()
{
bouton_thematique="x";
appel_deroulant(evt,"r_requete_thematique")
}
function y_thema()
{
bouton_thematique="y";
appel_deroulant(evt,"r_requete_thematique")
}
function centre_thema()
{
bouton_thematique="centre";
appel_deroulant(evt,"r_requete_thematique")
}
function plus_thema()
{
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"+");
}
function moins_thema()
{
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"-");
}
function multiple_thema()
{
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"*");
}
function divise_thema()
{
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"/");
}
function concate_thema()
{
svgdoc.getElementById ("r_requete_thematique").getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"||");
}
function appel_deroulant(evt,cible,x)
{
menu_derou=cible;
if(x!='appel')
{
var d=new Date();
if(x=="contenu")
{
var url="sql.php?objkey=contenu&d="+d.getHours()+'_'+d.getMinutes()+'_'+d.getSeconds();	
}
else if(x=="schema")
{
var url="sql.php?objkey=schema&d="+d.getHours()+'_'+d.getMinutes()+'_'+d.getSeconds();	
}
else if(x=="table")
{
var url="sql.php?objkey=table_"+pro_schema+"&d="+d.getHours()+'_'+d.getMinutes()+'_'+d.getSeconds();	
}
else
{
var url="sql.php?objkey="+pro_tabl+"_"+pro_schema+"&d="+d.getHours()+'_'+d.getMinutes()+'_'+d.getSeconds();	
}
getURL(url,retour_deroul)
}
else
{
	deroulinit="true";
	retour_deroul()

}

}
function ajout_the(evt)
{
svgdoc.getElementById ("r_theme").getFirstChild().setData(" ");
if(countvalfixe>0)
{
		for(var j=0;j<countvalfixe;j++)
	{
	objet=svgdoc.getElementById("tex_fixe_leg"+j);
	svgdoc.getElementById("valfixe").removeChild(objet);
	objet=svgdoc.getElementById("tex_filtre"+j);
	svgdoc.getElementById("valfixe").removeChild(objet);
	objet=svgdoc.getElementById("rec_fixe_leg"+j);
	svgdoc.getElementById("valfixe").removeChild(objet);
	objet=svgdoc.getElementById("rec_filtre"+j);
	svgdoc.getElementById("valfixe").removeChild(objet);
	}
	countvalfixe=0;
}
svgdoc.getElementById('ajout_the').style.setProperty('visibility','visible');	
}
function mod_the(evt)
{
svgdoc.getElementById ("r_mod_theme").getFirstChild().setData(" ");
svgdoc.getElementById('mod_the').style.setProperty('visibility','visible');	
}
function retourmodtheme(data)
{
var fragment=data.content.split("#");
svgdoc.getElementById ("r_mod_theme_nom").getFirstChild().setData(fragment[0].split("#"));
svgdoc.getElementById ("r_mod_schema").getFirstChild().setData(fragment[1].split("#"));
pro_schema=fragment[1].split("#");
svgdoc.getElementById ("r_mod_table").getFirstChild().setData(fragment[2].split("#"));
pro_tabl=fragment[2].split("#");
svgdoc.getElementById ("r_mod_shp").getFirstChild().setData(fragment[3].split("#"));
if(fragment[4].split("#")=='1')
{
svgdoc.getElementById('mod_partiel').style.setProperty ('visibility', 'visible')	
}
else
{
svgdoc.getElementById('mod_partiel').style.setProperty ('visibility', 'hidden')	
}
if(fragment[5].split("#")=='1')
{
svgdoc.getElementById('mod_initial').style.setProperty ('visibility', 'visible')
}
else
{
svgdoc.getElementById('mod_initial').style.setProperty ('visibility', 'hidden')	
}
svgdoc.getElementById ('r_mod_zmin').getFirstChild().setData(fragment[6].split("#"));
svgdoc.getElementById ('r_mod_zmax').getFirstChild().setData(fragment[7].split("#"));
svgdoc.getElementById ('r_mod_zmr').getFirstChild().setData(fragment[8].split("#"));
svgdoc.getElementById ('r_mod_ref').getFirstChild().setData(fragment[9].split("#"));
svgdoc.getElementById ('r_mod_geom').getFirstChild().setData(fragment[10].split("#"));
svgdoc.getElementById ('r_mod_lib').getFirstChild().setData(fragment[11].split("#"));
svgdoc.getElementById ('r_mod_clause').getFirstChild().setData(fragment[12].split("#"));
svgdoc.getElementById ('r_mod_groupe').getFirstChild().setData(fragment[13].split("#"));
svgdoc.getElementById('gestion_mod_theme').style.setProperty('visibility','visible');
}
function modif_theme()
{
svgdoc.getElementById('mod_the').style.setProperty('visibility','hidden');	
var url="intero_mod_theme.php?idtheme="+idtheme;
getURL(url,retourmodtheme)
}
function modification_theme()
{
nomtheme='';schema='';table='';raster='';groupe='';partiel=1;initial=1;zoommin='';zoommax='';zoommaxr='';ref='';libelle='';geom='';clause='';
nomtheme=svgdoc.getElementById("r_mod_theme_nom").getFirstChild().getData();
schema=svgdoc.getElementById("r_mod_schema").getFirstChild().getData();
table=svgdoc.getElementById("r_mod_table").getFirstChild().getData();
raster=svgdoc.getElementById("r_mod_shp").getFirstChild().getData();
groupe=svgdoc.getElementById("r_mod_groupe").getFirstChild().getData();
if (svgdoc.getElementById('mod_partiel').getStyle().getPropertyValue("visibility") == "hidden")
{
partiel="";	
}
if (svgdoc.getElementById('mod_initial').getStyle().getPropertyValue("visibility") == "hidden")
{
initial="";	
}
zoommin=svgdoc.getElementById("r_mod_zmin").getFirstChild().getData();
zoommax=svgdoc.getElementById("r_mod_zmax").getFirstChild().getData();
zoommaxr=svgdoc.getElementById("r_mod_zmr").getFirstChild().getData();
ref=svgdoc.getElementById("r_mod_ref").getFirstChild().getData();
libelle=svgdoc.getElementById("r_mod_lib").getFirstChild().getData();
geom=svgdoc.getElementById("r_mod_geom").getFirstChild().getData();
clause=svgdoc.getElementById("r_mod_clause").getFirstChild().getData();
if(schema==" "){schema=''}if(table==" "){table=''}if(raster==" "){raster=''}if(groupe==" "){groupe=''}if(zoommin==" "){zoommin='0'}if(zoommax==" "){zoommax='0'}if(zoommaxr==" "){zoommaxr='0'}if(ref==" "){ref=''}if(libelle==" "){libelle=''}if(geom==" "){geom=''}if(clause==" "){clause=''}
var url="modifie_theme.php?idtheme="+idtheme+"&nomtheme="+nomtheme+"&schema="+schema+"&table="+table+"&raster="+raster+"&groupe="+groupe+"&partiel="+partiel+"&initial="+initial+"&zoommin="+zoommin+"&zoommax="+zoommax+"&zoommaxr="+zoommaxr+"&ref="+ref+"&libelle="+libelle+"&geom="+geom+"&clause="+clause;
getURL(url,null)
ferme_fenetre(evt,'gestion_mod_theme')
}
function creer_theme()
{
//ferme_fenetre(evt,'gestion_creation_theme')
nomtheme='';schema='';table='';raster='';groupe='';partiel=1;initial=1;zoommin='';zoommax='';zoommaxr='';ref='';libelle='';geom='';clause='';
nomtheme=svgdoc.getElementById("r_theme_nom").getFirstChild().getData();
schema=svgdoc.getElementById("r_crea_schema").getFirstChild().getData();
table=svgdoc.getElementById("r_crea_table").getFirstChild().getData();
raster=svgdoc.getElementById("r_crea_shp").getFirstChild().getData();
groupe=svgdoc.getElementById("r_crea_groupe").getFirstChild().getData();
if (svgdoc.getElementById('crea_partiel').getStyle().getPropertyValue("visibility") == "hidden")
{
partiel="";	
}
if (svgdoc.getElementById('crea_initial').getStyle().getPropertyValue("visibility") == "hidden")
{
initial="";	
}
zoommin=svgdoc.getElementById("r_crea_zmin").getFirstChild().getData();
zoommax=svgdoc.getElementById("r_crea_zmax").getFirstChild().getData();
zoommaxr=svgdoc.getElementById("r_crea_zmr").getFirstChild().getData();
ref=svgdoc.getElementById("r_crea_ref").getFirstChild().getData();
libelle=svgdoc.getElementById("r_crea_lib").getFirstChild().getData();
geom=svgdoc.getElementById("r_crea_geom").getFirstChild().getData();
clause=svgdoc.getElementById("r_crea_clause").getFirstChild().getData();
if(schema==" "){schema=''}if(table==" "){table=''}if(raster==" "){raster=''}if(groupe==" "){groupe=''}if(zoommin==" "){zoommin='0'}if(zoommax==" "){zoommax='0'}if(zoommaxr==" "){zoommaxr='0'}if(ref==" "){ref=''}if(libelle==" "){libelle=''}if(geom==" "){geom=''}if(clause==" "){clause=''}
var url="ajout_theme.php?nomtheme="+nomtheme+"&schema="+schema+"&table="+table+"&raster="+raster+"&groupe="+groupe+"&partiel="+partiel+"&initial="+initial+"&zoommin="+zoommin+"&zoommax="+zoommax+"&zoommaxr="+zoommaxr+"&ref="+ref+"&libelle="+libelle+"&geom="+geom+"&clause="+clause;
getURL(url,retour_crea_theme)
}
function retour_crea_theme(data)
{
ferme_fenetre(evt,'gestion_creation_theme')
string=data.content.split("|");
idtheme=string[1];
if (string[0]=='point') 
{
mod_theme='creation'
svgdoc.getElementById('choix_pt').style.setProperty ('visibility', 'visible');
//alert('point')
}
else if(string[0]=='line')
{
mod_theme='creation'
svgdoc.getElementById('choix_lg').style.setProperty ('visibility', 'visible');
idtheme=string[1];	
}

}
function valid_choix_pt()
{
	ferme_fenetre(evt,'choix_pt')
	var url="ajout_style.php?idtheme="+idtheme+"&mod_theme="+mod_theme+"&type=point&affecte="+svgdoc.getElementById("r_choix_pt").getFirstChild().getData();
getURL(url,null)
idtheme="";
}
function valid_choix_lg()
{
	ferme_fenetre(evt,'choix_lg')
	var url="ajout_style.php?idtheme="+idtheme+"&mod_theme="+mod_theme+"&type=line&affecte="+svgdoc.getElementById("r_choix_lg").getFirstChild().getData();
getURL(url,null)
idtheme="";
}
function retour_deroul(data)
{
	
if (svgdoc.getElementById('deroul').getStyle().getPropertyValue("visibility") == "hidden")
{
	if(deroulinit=="true")
	{
	var fragment=new Array;
	if(menu_derou=='r_choix_pt')
	{
	fragment[0]="Texte";
	fragment[1]="Symbole";
	fragment[2]="";
	}
	else if(menu_derou=='r_choix_lg')
	{
	fragment[0]="trace";
	fragment[1]="libelle";
	fragment[2]="";
	}
	else if(menu_derou=='r_applique')
	{
	fragment[0]="valeur fixe";
	fragment[1]="fourchette";
	fragment[2]="";
	}
	else
	{
	fragment[0]="Style par d"+String.fromCharCode(233)+"faut";
	fragment[1]="Th"+String.fromCharCode(232)+"matique";
	fragment[2]="";
	}
	deroulinit=""
	}
	else
	{
var fragment=convasc_inverse(data.content).split("#");
	}
if(menu_derou=="r_requete_thematique")
{
	
	if(bouton_thematique=="surface")
	{
		rey=svgdoc.getElementById("surface_thematique").getAttribute('y');
		rex=svgdoc.getElementById("surface_thematique").getAttribute('x');
		rew=60;
		reh=svgdoc.getElementById("surface_thematique").getAttribute('height');
	}
	else if(bouton_thematique=="centre")
	{
		rey=svgdoc.getElementById("centre_thematique").getAttribute('y');
		rex=svgdoc.getElementById("centre_thematique").getAttribute('x');
		rew=60;
		reh=svgdoc.getElementById("centre_thematique").getAttribute('height');
	}
	else if(bouton_thematique=="longueur")
	{
		rey=svgdoc.getElementById("longueur_thematique").getAttribute('y');
		rex=svgdoc.getElementById("longueur_thematique").getAttribute('x');
		rew=60;
		reh=svgdoc.getElementById("longueur_thematique").getAttribute('height');
	}
	else if(bouton_thematique=="souschaine")
	{
		rey=svgdoc.getElementById("souschaine_thematique").getAttribute('y');
		rex=svgdoc.getElementById("souschaine_thematique").getAttribute('x');
		rew=60;
		reh=svgdoc.getElementById("souschaine_thematique").getAttribute('height');
	}
	else if(bouton_thematique=="x")
	{
		rey=svgdoc.getElementById("x_thematique").getAttribute('y');
		rex=svgdoc.getElementById("souschaine_thematique").getAttribute('x');
		rew=60;
		reh=svgdoc.getElementById("x_thematique").getAttribute('height');
	}
	else if(bouton_thematique=="y")
	{
		rey=svgdoc.getElementById("y_thematique").getAttribute('y');
		rex=svgdoc.getElementById("y_thematique").getAttribute('x');
		rew=60;
		reh=svgdoc.getElementById("y_thematique").getAttribute('height');
	}
	else
	{
		rey=parseFloat(svgdoc.getElementById("requete_thema").getAttribute('y'));
		rex=svgdoc.getElementById("requete_thema").getAttribute('x')-60;
		rew=60;
		reh=parseFloat(svgdoc.getElementById("requete_thema").getAttribute('height'));
	
	}
	
}
else
{
rey=svgdoc.getElementById("rect_"+menu_derou).getAttribute('y');
rex=svgdoc.getElementById("rect_"+menu_derou).getAttribute('x');
rew=svgdoc.getElementById("rect_"+menu_derou).getAttribute('width');
reh=svgdoc.getElementById("rect_"+menu_derou).getAttribute('height');
}
new_h_rect_deroul=(fragment.length-1)*10+5;
htmenu=new_h_rect_deroul;
yy=parseFloat(rey)+parseFloat(reh);
xx=parseFloat(rex)+parseFloat(rew);
if(new_h_rect_deroul>200)
{
svgdoc.getElementById("rect_de1").setAttributeNS(null,'visibility','visible');
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'visibility','visible');	
svgdoc.getElementById('scroll_cursor_deroul').setAttributeNS(null,'y',yy);
svgdoc.getElementById('scroll_cursor_deroul').setAttributeNS(null,'x',xx);
//svgdoc.getElementById('rect_de').setAttributeNS(null,'width',rew);
svgdoc.getElementById('scroll_cursor_deroul').setAttributeNS(null,'height',200-(fragment.length-1));
new_h_rect_deroul=200;
xw=parseFloat(rew)+12;
xxx=parseFloat(rew)+10;
}
else
{
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("tderoul").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("rect_de1").setAttributeNS(null,'visibility','hidden');
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'visibility','hidden');
xw=parseFloat(rew);
xxx=parseFloat(rew);
}
yyy=yy+10;
svgdoc.getElementById('rect_de1').setAttributeNS(null,'y',yy);
svgdoc.getElementById('rect_de1').setAttributeNS(null,'x',xx);
svgdoc.getElementById('rect_de1').setAttributeNS(null,'height',new_h_rect_deroul);
svgdoc.getElementById('rect_de').setAttributeNS(null,'y',yy);
svgdoc.getElementById('rect_de').setAttributeNS(null,'x',rex);

svgdoc.getElementById('rect_de').setAttributeNS(null,'width',xxx);
svgdoc.getElementById('rect_de').setAttributeNS(null,'height',new_h_rect_deroul);
svgdoc.getElementById('rect_deroul').setAttributeNS(null,'y',yy);
svgdoc.getElementById('rect_deroul').setAttributeNS(null,'x',rex);
svgdoc.getElementById('rect_deroul').setAttributeNS(null,'width',xw);
svgdoc.getElementById('rect_deroul').setAttributeNS(null,'height',new_h_rect_deroul);
svgdoc.getElementById('rect_deroul').setAttributeNS(null,'viewBox',rex+" "+yy+" "+xw+" "+new_h_rect_deroul)

if(countrait>0)
{
		for(var j=0;j<countrait;j++)
	{
	objet=svgdoc.getElementById("tex_deroul"+j);
	svgdoc.getElementById("tderoul").removeChild(objet);
	}
	countrait=0;
}
	for (k=0; k<fragment.length; k++)
		{
		countrait=countrait+1;
		texte=svgDocument.createElementNS(null, 'text');
texte.setAttribute("class","fillfonce");
texte.setAttribute("id","tex_deroul"+k)
xxxx=parseFloat(rex)+2;
texte.setAttribute("x",xxxx);
texte.setAttribute("y",yyy+(k*10));
texte.setAttribute("onmouseover","switchColor(evt,'fill','red','','')");
texte.setAttribute("onmouseout","switchColor(evt,'fill','url(#survol)','','')");
if(menu_derou=='r_theme' || menu_derou=='r_mod_theme')
{
	zz="";z="";
	z=fragment[k];
	zz=z.split(".");
	texte.setAttribute("onclick","insert_val_deroul('"+zz[0]+"','"+zz[1]+"')");
	text_content = svgDocument.createTextNode(zz[0]);
}
else
{
texte.setAttribute("onclick","insert_val_deroul('"+fragment[k].split("#")+"')");
text_content = svgDocument.createTextNode(fragment[k].split("#"));
}
texte.appendChild(text_content)
svgDocument.getElementById("tderoul").appendChild(texte)
}
svgdoc.getElementById('deroul').style.setProperty ('visibility', 'visible');
}
else
{
svgdoc.getElementById('deroul').style.setProperty ('visibility', 'hidden');
svgdoc.getElementById("rect_de1").setAttributeNS(null,'visibility','hidden');
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'visibility','hidden');
if(countrait>0)
{
		for(var j=0;j<countrait;j++)
	{
	objet=svgdoc.getElementById("tex_deroul"+j);
	svgdoc.getElementById("tderoul").removeChild(objet);
	}
	countrait=0;
}
}
}
function insert_val_deroul(valeur,valeur2)
{
if(menu_derou=="r_requete_thematique")
{
if(bouton_thematique=="surface")
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"area("+valeur+")");
}
else if(bouton_thematique=="centre")
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"centroid("+valeur+")");
}
else if(bouton_thematique=="longueur")
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"length("+valeur+")");
}
else if(bouton_thematique=="souschaine")
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"substr("+valeur+",debut,longueur)");
}
else if(bouton_thematique=="x")
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"x("+valeur+")");
}
else if(bouton_thematique=="y")
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+"y("+valeur+")");
}
else
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(svgdoc.getElementById("r_requete_thematique").getFirstChild().getData()+valeur);	
}
bouton_thematique="";
}
else
{
svgdoc.getElementById (menu_derou).getFirstChild().setData(valeur);	
}
if(menu_derou=="r_crea_schema")
{
	pro_schema=valeur
	svgdoc.getElementById ("r_crea_table").getFirstChild().setData(" ");
	svgdoc.getElementById('g_tabl').setAttributeNS(null,'pointer-events','visible');
	svgdoc.getElementById('g_tabl').setAttributeNS(null,'opacity','1');
}
if(menu_derou=="r_crea_table")
{
	pro_tabl=valeur
	svgdoc.getElementById('g_ref').setAttributeNS(null,'pointer-events','visible');
	svgdoc.getElementById('g_ref').setAttributeNS(null,'opacity','1');
	svgdoc.getElementById('g_geom').setAttributeNS(null,'pointer-events','visible');
	svgdoc.getElementById('g_geom').setAttributeNS(null,'opacity','1');
	svgdoc.getElementById('g_lib').setAttributeNS(null,'pointer-events','visible');
	svgdoc.getElementById('g_lib').setAttributeNS(null,'opacity','1');
}
if(menu_derou=="r_mod_schema")
{
	pro_schema=valeur
	svgdoc.getElementById ("r_mod_table").getFirstChild().setData(" ");
}
if(menu_derou=="r_mod_table")
{
	pro_tabl=valeur
}
if(valeur2)
{
idtheme=valeur2;
}
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'transform','translate(0,0)');
svgdoc.getElementById("tderoul").setAttributeNS(null,'transform','translate(0,0)')
svgdoc.getElementById('deroul').style.setProperty ('visibility', 'hidden');
svgdoc.getElementById("rect_de1").setAttributeNS(null,'visibility','hidden');
svgdoc.getElementById("scroll_cursor_deroul").setAttributeNS(null,'visibility','hidden');
if(countrait>0)
{
		for(var j=0;j<countrait;j++)
	{
	objet=svgdoc.getElementById("tex_deroul"+j);
	svgdoc.getElementById("tderoul").removeChild(objet);
	}
	countrait=0;
}
}
function verif_theme()
{
	if(svgdoc.getElementById("r_theme").getFirstChild().getData()!=" " && svgdoc.getElementById("r_theme").getFirstChild().getData()!="")
	{
	ferme_fenetre(evt,'ajout_the')
	var d=new Date();
	url='propriete_theme.php?objkey=' + idtheme +'&d=' + d.getHours()+'_'+d.getMinutes()+'_'+d.getSeconds()+'&indice=raster';
	getURL(url,retour_verif_theme)
	}
}
function retour_verif_theme(data)
{
	var fragment=data.content.split("#");
	pro_schema=fragment[1].split("#")
	pro_tabl=fragment[2].split("#")
	if(fragment[0].split("#")=='false')
	{
	svgdoc.getElementById('app_style_thema').style.setProperty ('visibility', 'visible');
	svgdoc.getElementById('rect_r_style_thema').setAttributeNS(null,'pointer-events','visible');
	svgdoc.getElementById('rect1_style').setAttributeNS(null,'pointer-events','visible');
	}
	else
	{
	svgdoc.getElementById('app_style_thema').style.setProperty ('visibility', 'visible');	
	svgdoc.getElementById('rect_r_style_thema').setAttributeNS(null,'pointer-events','none');
	svgdoc.getElementById('rect1_style').setAttributeNS(null,'pointer-events','none');
	svgdoc.getElementById ("r_style_thema").getFirstChild().setData("Style par d"+String.fromCharCode(233)+"faut");
	}
}
function valid_style_them()
{
	if(svgdoc.getElementById("r_style_thema").getFirstChild().getData()=="Style par d"+String.fromCharCode(233)+"faut")
	{
	svgdoc.getElementById ("r_style_thema").getFirstChild().setData(" ");	
	url="update_style.php?appli="+appli+"&idtheme="+idtheme;
	getURL(url,null)
	ferme_fenetre(evt,'app_style_thema')
		var zer="li_style_theme";
var url='http://'+serveur+'/interface/back_office/back_office.php';
var param="";
ouvrepage(url,param,zer)
	}
	else if(svgdoc.getElementById("r_style_thema").getFirstChild().getData()=="Th"+String.fromCharCode(232)+"matique")
	{
	svgdoc.getElementById ("r_style_thema").getFirstChild().setData(" ");	
	svgdoc.getElementById('gestion_thematique').style.setProperty ('visibility', 'visible');	
	ferme_fenetre(evt,'app_style_thema')
	}
	
}
function get_insert_ad_id()
{
	val1=svgdoc.getElementById("r_ad").getFirstChild().getData();
	val2=svgdoc.getElementById("r_id").getFirstChild().getData();
	if(pro_ad=="")
	{
	requete_ad="insert into admin_svg.col_sel (idtheme,nom_as,appel) values ('"+idtheme+"','ad','"+val1+"')";	
	pro_ad=val1;
	}
	else
	{
	requete_ad="UPDATE admin_svg.col_sel SET appel='"+val1+"' where idtheme='"+idtheme+"' and nom_as='ad'";
	}
	url1="execute_sql.php?requete="+requete_ad+"&genere=false";
	getURL(url1,null)
	if(pro_ident=="")
	{
	requete_id="insert into admin_svg.col_sel (idtheme,nom_as,appel) values ('"+idtheme+"','ident','"+val2+"')";	
	pro_ident=val2;
	}
	else
	{
	requete_id="UPDATE admin_svg.col_sel SET appel='"+val2+"' where idtheme='"+idtheme+"' and nom_as='ident'";
	}
	url2="execute_sql.php?requete="+requete_id+"&genere=false";
	getURL(url2,null)
	ferme_fenetre(evt,'ajout_ad_id')
	
}

function affiche_script(x)
{
	svgdoc.getElementById(x).style.setProperty ('visibility', 'visible');
}
function supp_couche(x)
{
		
	url="update_style.php?choix=supp&idappthe="+x;
	getURL(url,null)
	var zer="li_info_supp";
var url='http://'+serveur+'/interface/back_office/back_office.php';
var param="";
ouvrepage(url,param,zer)
svgdoc.getElementById('info').style.setProperty ('visibility', 'hidden');
}
function bascule(evt,cible)
{
var objet=svgdoc.getElementById(cible);
if (objet.getStyle().getPropertyValue("visibility") == "hidden")
	{
	objet.getStyle().setProperty('visibility','visible')
	}
	else
	{
		objet.getStyle().setProperty('visibility','hidden')
	}
}
function genere_script(cible)
{
	if(cible=='mouseover')
	{
		scriptover="sur(evt,'"+svgdoc.getElementById("r_survol").getFirstChild().getData()+"'";
		if((svgdoc.getElementById("r_message1").getFirstChild().getData()!="!" && svgdoc.getElementById("r_message1").getFirstChild().getData()!="") || (svgdoc.getElementById("r_message2").getFirstChild().getData()!="!" && svgdoc.getElementById("r_message2").getFirstChild().getData()!=""))
		{
			mess1=svgdoc.getElementById("r_message1").getFirstChild().getData();
			mess2=svgdoc.getElementById("r_message2").getFirstChild().getData();
			if(mess1=="!")
			{
				mess1=""
			}
			if(mess2=="!")
			{
				mess2=""
			}
		scriptover+=",'"+mess1+"','"+mess2+"'";	
		}
		scriptover+=")";
		ferme_fenetre(evt,'script_mouseover')
		svgdoc.getElementById ("r_mouseover").getFirstChild().setData(scriptover);
		svgdoc.getElementById ("r_mouseout").getFirstChild().setData('hors(evt)');
		svgdoc.getElementById ("r_survol").getFirstChild().setData("!");
		svgdoc.getElementById ("r_message1").getFirstChild().setData("!");
		svgdoc.getElementById ("r_message2").getFirstChild().setData("!");
		
	}
	if(cible=='click')
	{
		scriptclick="lien(evt,'"+svgdoc.getElementById("r_nomobjet").getFirstChild().getData()+"',";
		if (svgdoc.getElementById ("securise").getStyle().getPropertyValue("visibility") == "visible")
			{
			scriptclick+="'https://";	
			}
			else
			{
			scriptclick+="'http://";
			}
			
			if(svgdoc.getElementById("r_serveur").getFirstChild().getData()=='serveur')
			{
				chaineserve="'+serveur+'";
			}
			else
			{
				chaineserve=svgdoc.getElementById("r_serveur").getFirstChild().getData();
			}
		scriptclick+=chaineserve+"/"+svgdoc.getElementById("r_chemin").getFirstChild().getData()+"?"+svgdoc.getElementById("r_variable").getFirstChild().getData()+"=',";
		if (svgdoc.getElementById ("multip").getStyle().getPropertyValue("visibility") == "visible")
			{
			scriptclick+="'m','"+svgdoc.getElementById("r_limite").getFirstChild().getData()+"')";	
			}
			else
			{
			scriptclick+="'','')";
			}
			ferme_fenetre(evt,'script_mouseclick')
		svgdoc.getElementById ("r_click").getFirstChild().setData(scriptclick);
		svgdoc.getElementById ("r_nomobjet").getFirstChild().setData('!');
		svgdoc.getElementById ("r_serveur").getFirstChild().setData("serveur");
		svgdoc.getElementById ("r_chemin").getFirstChild().setData("!");
		svgdoc.getElementById ("r_limite").getFirstChild().setData("2");
	}
}
