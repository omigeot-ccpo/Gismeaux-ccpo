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
var varcotations=new Array,ini_use=0;
function init(evt) 
	{
HTTP()
	svgdoc=evt.target.ownerDocument
	svgdoc.getElementById('droite').firstChild.data=parseFloat(parseInt((zoom_init/100)*(largeurini/620)*90)/1000);
	svgdoc.getElementById('centre').firstChild.data=parseFloat(parseInt((zoom_init/100)*(largeurini/620)*45)/1000);
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
		zoomin=svgdoc.getElementById('loupe');
		zoomin.setAttributeNS(null,'pointer-events','none');
		zoomin.setAttributeNS(null,'opacity','0.5');
		zoomin=svgdoc.getElementById('plus');
		zoomin.setAttributeNS(null,'pointer-events','none');
		zoomin.setAttributeNS(null,'opacity','0.2');
		}
	else
		{
		zoomin=svgdoc.getElementById('loupe');
		zoomin.setAttributeNS(null,'pointer-events','visible');
		zoomin.setAttributeNS(null,'opacity','1');
		zoomin=svgdoc.getElementById('plus');
		zoomin.setAttributeNS(null,'pointer-events','visible');
		zoomin.setAttributeNS(null,'opacity','1');
		}
		initScaleBar(evt);
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

function initScaleBar(evt)
{
	svgobj = svgdoc.getElementById('droite');
	childobj = svgobj.getFirstChild();
	childobj.setData(parseFloat(parseInt((zoom_init/zoomVal)*(largeurini/620)*90)/1000));
	svgobj = svgdoc.getElementById('centre');
	childobj = svgobj.getFirstChild();
	childobj.setData(parseFloat(parseInt((zoom_init/zoomVal)*(largeurini/620)*45)/1000));
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
//message("toto")
	getURL(url,retour_extract)
	couchesvgvisible="";
	}
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
	message('Impossible de charger cette couche...')
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

function liste_glisse(evt)
{
if (liste_scroll_appui=="true")
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
function impression()
{
	couches="";
	var nb = layer.length;
	var reg=new RegExp("( )", "g");
	var coucherasteraimprimer=coucherastervisible.replace(reg,"_");
	coucherasteraimprimer=coucherasteraimprimer.substr(0,coucherasteraimprimer.length-1)
	couches="";
	if(coucherasteraimprimer!="" || coucherasteraimprimer!=";" || coucherasteraimprimer!=";;")
	   {
		couches=coucherasteraimprimer+";";
	   }
	 var separ=new RegExp("[;]+", "g");
	
			var tableau=couches.split(separ);  
	for (b=0;b<nb;b++)
	{
		var reg1=new RegExp(layer[b],"g");

		if(zlayer[layer[b]].svg_visible=='true')
		{
			couche=layer[b].replace(reg,"_");
			
			 //alert(layer[b])
				var layer_test=layer[b].replace(reg,"_");
				//var reg1=new RegExp("["+layer_test+"]","g");
				
				if (couches.match(layer_test)) 
				{
				}
				else
				{
					if(couches=="")
					{
					couches +=couche;
					}
					else
					{
					couches +=";"+couche;
					}
						
				}
			
		}
	}
	if(nav!="0")
	{
		couches=convasc(couches);
	}
	var nb = varcotations.length;
		for(a=0;a<nb;a++)
		{
		getURL("cotation.php?eff=0&num="+a+"&cot="+varcotations[a],callback2);
		}
		if(nb==0)
		{
		getURL("cotation.php?eff=1",callback2);	
		}
		if(parce!="")
		{
			ajo=","+parce+")";
		}
		else
		{
			ajo=")";
		}
	var url="https://" + serveur + "/interface/preprint.php?nav="+nav+"&raster="+couches+"&x="+ xnulcorner + "&y=" + ynulcorner + "&lar=" + nWidth  + "&hau=" + nHeight+ "&zoom=" + zoomVal  + "&xini=" + xini + "&yini=" + yini +"&nav="+nav+"&sessionname=" + sessionname + "&sessionid=" + sessionid +"&echini="+(nWidth*3300/620)+"&parce=('"+placid+"'"+ajo;
var param="_blank";
var zer="liprint";
ouvrepage(url,param,zer)
couches="";
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
function entre_dim(evt)
{
tape_autorise=1;
svgdoc.getElementById("x_rect").getFirstChild().setData("");
phrase="";
Periode=setInterval("clignotement(evt,'cligno1')", 500);
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
if (phrase.length<17) 
{
phrase=phrase+lettre;
}
}
var val=parseFloat(646+(phrase.length)*4.8)
svgdoc.getElementById("x_rect").getFirstChild().setData(phrase);
svgdoc.getElementById('cligno1').setAttributeNS(null,'x',val)
}
}
function recherche()
{
clearInterval(Periode);
svgdoc.getElementById('cligno1').style.setProperty('visibility','hidden')	
tape_autorise=0;
about = svgdoc.getElementById('inforecherche');
about.getStyle().setProperty('visibility','visible');
extraire_recherche()
}
function hideAbout()
{
about = svgdoc.getElementById('inforecherche');
about.getStyle().setProperty('visibility','hidden');
svgdoc.getElementById("numer").setAttribute("visibility","hidden");
}
function extraire_recherche()
{
objet=svgdoc.getElementById("x_rect").getFirstChild().getData();
svgdoc.getElementById("x_rect").getFirstChild().setData("?");
svgdoc.getElementById('cligno1').setAttributeNS(null,'x','646')
url='cherche.php?indice=' + objet + '&sessionname=' + sessionname + '&sessionid=' + sessionid + '&code_insee=' + code_insee;

getURL(url,retour)
}
function retour(data)
{
 var string = '';
    if(data.success) {
      string = data.content;
//alert(string)
    var docFragment=parseXML(string, document);
	afficherecherche = svgdoc.getElementById('controlrecherche');
var content = document.getElementById('controlrecherche');
    content.appendChild(docFragment);
	}
}
function rechercheavance(evt)
{
node=evt.getTarget();
var routage = node.getAttribute("n");
var routage2= node.getAttribute("r");
if (routage==1)
{
var parcelle=node.getAttribute("id")
chargement=1;

var url='locali.php?parcelle=' + parcelle + '&sessionname=' + sessionname + '&sessionid=' + sessionid + '&xini=' + xini + '&yini=' + yini;
getURL(url,retourrecherche)
}
else
{
var cod=node.getAttribute("id");
var texteadresse=node.getFirstChild().getData();
codeadresse=node.getFirstChild().getData();
svgdoc.getElementById("adress").getFirstChild().setData(texteadresse);
svgdoc.getElementById("adre").getFirstChild().setData(cod);
svgdoc.getElementById("x_rectnum").getFirstChild().setData("?");
svgdoc.getElementById("numer").setAttribute("visibility","visible");
}
}
function retourrecherche(x)
{
chargement=0;
var fragment=x.content.split(";");
placid=fragment[0].split(";");
placx=fragment[1].split(";");
placy=fragment[2].split(";");
if(placid=='point')
{
message("La num"+String.fromCharCode(233)+"rotation n'est pas valide la carte est positionn"+String.fromCharCode(233)+"e automatiquement sur la rue.");
}
else
{
hideAbout()}
rectOvewidth = parseFloat(svgRect.getAttributeNS(null,'width'));
rectOveheight = parseFloat(svgRect.getAttributeNS(null,'height'));
var x1=placx-(rectOvewidth/2);
var y1=Math.abs(placy)-(rectOveheight/2);
svgRect.setAttributeNS(null,'x',x1);
svgRect.setAttributeNS(null,'y',y1);
xnulcorner=parseFloat(svgRect.getAttributeNS(null,'x'));
ynulcorner=parseFloat(svgRect.getAttributeNS(null,'y'));;
	lin1.setAttributeNS(null,'x',placx-((Rect1lar/6)/2));
	lin1.setAttributeNS(null,'y',Math.abs(placy));
	lin2.setAttributeNS(null,'x',placx);
	lin2.setAttributeNS(null,'y',Math.abs(placy)-((Rect1hau/6)/2));
	Rect1.setAttributeNS(null,'x',placx-((Rect1lar/6)/2));
	Rect1.setAttributeNS(null,'y',Math.abs(placy)-((Rect1hau/6)/2));	
	rectOveXcorner = parseFloat(svgRect.getAttributeNS(null,'x'));
	rectOveYcorner = parseFloat(svgRect.getAttributeNS(null,'y'));
	y = svgMainViewport.currentTranslate.y;
	x = svgMainViewport.currentTranslate.x;
	rectOveYcorner = parseFloat(rectOveYcorner + (y*100/zoomVal));
	rectOveXcorner = parseFloat(rectOveXcorner + (x*100/zoomVal));
	newViewport = xnulcorner + ' ' + ynulcorner + ' ' + rectOvewidth + ' ' + rectOveheight;
	svgMainViewport.setAttributeNS(null,'viewBox',newViewport);
Zoomto(evt,zoomrecherche)
svgdoc.getElementById("x_rect").getFirstChild().setData("?");
svgdoc.getElementById("x_rectnum").getFirstChild().setData("?");
}
function recher()
{
tapenum_autorise=0	
var adresse=svgdoc.getElementById("adre").getFirstChild().getData();
var num=svgdoc.getElementById("x_rectnum").getFirstChild().getData();
svgdoc.getElementById("x_rectnum").getFirstChild().setData("?");
hideAbout()
var url='locali.php?parcelle=&code=' + adresse + '&numero=' + num + '&sessionname=' + sessionname + '&sessionid=' + sessionid;
getURL(url,retourrecherche)
}
function entre_dimnum(evt)
{
tapenum_autorise=1	
svgdoc.getElementById("x_rectnum").getFirstChild().setData("");
phrase1="";
Periode=setInterval("clignotement(evt,'cligno2')", 500);
}
function tapenum(evt)
{
clearInterval(Periode);
svgdoc.getElementById('cligno2').style.setProperty('visibility','hidden')	

if(tapenum_autorise=1)
{
key1 = evt.getCharCode();	
if ((key1==8)&&(phrase1!="")) 
{
phrase1=phrase1.substring(0,phrase1.length-1)
}
else
{
lettre1 = String.fromCharCode(key1);
if (phrase1.length<17) 
{
phrase1=phrase1+lettre1;
}
} 
svgdoc.getElementById("x_rectnum").getFirstChild().setData(phrase1);
}

}
function activetrait(evt)
{
svgdoc.getElementById("dessin").setAttribute("pointer-events","none");
svgdoc.getElementById("desrect").setAttribute("pointer-events","visible");
noactive=1;
if(bouton_distance==0)
{
clearobj()
cotation=0;
distance=1;
surface=0;
polygon=0;
indice="distance";
cumul=0;
longtrait=0;
svgdoc.getElementById("boutonregle").setAttribute("onmouseover","showinfotip(evt,'Mesure de distance')");
showinfotip(evt,'Mesure de distance')
svgdoc.getElementById("outi").getFirstChild().setData("j");
svgdoc.getElementById("outi").setAttribute("x",709);
}
else if(bouton_distance==1)
{
clearobj()	
svgdoc.getElementById("effacemesure").setAttribute("visibility","hidden");
cotation=1;
distance=0;
surface=0;
indice="cotation";
svgdoc.getElementById("boutonregle").setAttribute("onmouseover","showinfotip(evt,'Cotation')");
showinfotip(evt,'Cotation')
svgdoc.getElementById("outi").getFirstChild().setData("ij");
svgdoc.getElementById("outi").setAttribute("x",705.5);
cumul=0;
}
else
{
clearobj()
cumul=0;
polygon=0;
svgdoc.getElementById("effacemesure").setAttribute("visibility","hidden");	
cotation=0;
distance=0;
surface=1;
indice="surface";
polyg=""
svgdoc.getElementById("boutonregle").setAttribute("onmouseover","showinfotip(evt,'Mesure de surface')");
showinfotip(evt,'Mesure de surface')
svgdoc.getElementById("outi").getFirstChild().setData("n");
svgdoc.getElementById("outi").setAttribute("x",709);
}
if(indice!="surface")
{bouton_distance+=1}
else
{bouton_distance=0}
traitpret=0
}
function createcircle(evt)
{
	
cumul=parseFloat(cumul+longtrait);
if(surface==1)
{
traitrouge(evt)
surface=2
traitpret=1
lx=xm;
ly=ym;
polyg = parseFloat(xini+xm)+ " " + parseFloat(yini-ym);
		polyfin=polyg;

}
else if(surface==2)
{
			if(ligne)
			{
			objet=svgdoc.getElementById("traits2");
			svgdoc.getElementById("dess").removeChild(objet);
			}
if(polygon==1)
{
svgdoc.getElementById("effacepolygo").setAttribute("visibility","visible");
svgdoc.getElementById("validepolygo").setAttribute("visibility","visible");
}
else
{
svgdoc.getElementById("effacesurface").setAttribute("visibility","visible");
svgdoc.getElementById("validesurface").setAttribute("visibility","visible");
}
countsurface=countsurface+1;
traitblue(evt)
polyg += ","+parseFloat(xini+xm)+ " " + parseFloat(yini-ym);	
lxdist=xm;
lydist=ym;
lx=xm;
ly=ym;
traitrouge(evt)
}
if(distance==1)
{
	
traitrouge(evt)
distance=2
traitpret=1
lx=xm;
ly=ym;
}
else if(distance==2)
{
			if(ligne)
			{
			objet=svgdoc.getElementById("traits2");
			svgdoc.getElementById("dess").removeChild(objet);
			}

svgdoc.getElementById("effacemesure").setAttribute("visibility","visible");
countrait=countrait+1;
traitblue(evt)
lxdist=xm;
lydist=ym;
lx=xm;
ly=ym;
traitrouge(evt)
affichetext(evt)
hideinfotip(evt);
}
if(cotation==1)
{
	if(lx!=0)
	{
		objet=svgdoc.getElementById("traits2");
		svgdoc.getElementById("dess").removeChild(objet);
	}
	lxdist=0;
	lydist=0;
	traitrouge(evt)
cotation=2
traitpret=1
lx=xm;
ly=ym;
}
else if(cotation==2)
{
			if(ligne)
			{
			objet=svgdoc.getElementById("traits2");
			svgdoc.getElementById("dess").removeChild(objet);
			}
svgdoc.getElementById("effacemesure").setAttribute("visibility","visible");
countcotation=countcotation+1;
traitblue(evt)
cotation=1
traitpret=0
lx=0;
ly=0;
affichetext(evt)
hideinfotip(evt);
}
}
function bougetrait(evt)
{

if(traitpret==1)
{
coordonne(evt)	
svgdoc.getElementById("traits2").setAttribute("x2",(xm));
svgdoc.getElementById("traits2").setAttribute("y2",(ym));
var carx=Math.pow(parseFloat(lx-xm),2);
var cary=Math.pow(parseFloat(ly-ym),2);
longtrait=Math.sqrt(carx+cary);
valtrait=Math.round(longtrait*100)/100
if(lx<=xm && ly<=ym)
{
cosa=(ly-ym)/longtrait;
angle=(180*Math.acos(cosa)/Math.PI)-90;

}
else if(lx<=xm && ly>=ym)
{
cosa=(ly-ym)/longtrait;
angle=270+(180*Math.acos(cosa)/Math.PI);
}
else if(lx>=xm && ly<=ym)
{
cosa=(ym-ly)/longtrait;
angle=270+(180*Math.acos(cosa)/Math.PI);
}
else
{
cosa=(ym-ly)/longtrait;
angle=-90+(180*Math.acos(cosa)/Math.PI);
}
posix=lx+((xm-lx)/2);
posiy=ly+((ym-ly)/2);
if(surface==0)
{
showinfotip (evt,' '+valtrait+' ')
}
}

}
function fintrait(evt)
{
if(traitpret==1)
	{
lxpress=1;
	}
}
function effacetrait(evt)
{
if(indice=='surface')
{
var de=countsurface;
}
else if(indice=='cotation')
{
	var de=countcotation;
}
else
{
	var de=countrait;
}
var xold=svgdoc.getElementById(indice+de).getAttribute("x1");
var yold=svgdoc.getElementById(indice+de).getAttribute("y1");
objet=svgdoc.getElementById(indice+de);
svgDocument.getElementById("dess").removeChild(objet);
if(indice=="distance")
{
	for(var j=1;j<countrait;j++)
	{
	objet=svgdoc.getElementById(indice+j);
	svgdoc.getElementById("dess").removeChild(objet);
	objet=svgdoc.getElementById("tex"+indice+j);
	svgdoc.getElementById("dess").removeChild(objet);
	}
	objet=svgdoc.getElementById("tex"+indice+countrait);
	svgdoc.getElementById("dess").removeChild(objet);
countrait=0;		
count=countrait;
cumul=0;
longtrait=0;
}
if(indice!="cotation")
{
svgdoc.getElementById("traits2").setAttribute("x1", xold);
svgdoc.getElementById("traits2").setAttribute("y1", yold);
}
if(indice=='surface')
{
countsurface=countsurface-1;
count=countsurface;
lx=xold
ly=yold
}
else if(indice=='cotation')
{
objet=svgdoc.getElementById("tex"+indice+de);
svgdoc.getElementById("dess").removeChild(objet);
//if(nav!="0")
//{
varcotations.pop()
//}
countcotation=countcotation-1;
count=countcotation;
lx=xold
ly=yold
}
if(count==0)
{
	if(indice=="surface")
	{
		if(polygon==1)
		{
	svgdoc.getElementById("effacepolygo").setAttribute("visibility","hidden");	
	svgdoc.getElementById("validepolygo").setAttribute("visibility","hidden");
		}
		else
		{
	svgdoc.getElementById("effacesurface").setAttribute("visibility","hidden");	
	svgdoc.getElementById("validesurface").setAttribute("visibility","hidden");
		}
	traitpret=0
	surface=1
	lxdist=0
	lx=0
	ly=0
	}
	if(indice=="cotation")
	{
	svgdoc.getElementById("effacemesure").setAttribute("visibility","hidden");	
	traitpret=0
	cotation=1
	lxdist=0
	lx=0
	ly=0
	}
	if(indice=="distance")
	{
	svgdoc.getElementById("effacemesure").setAttribute("visibility","hidden");
	traitpret=0
	distance=1
	lxdist=0
	lx=0
ly=0
	}
if(ligne && indice!="cotation")
			{
			objet=svgdoc.getElementById("traits2");
			svgdoc.getElementById("dess").removeChild(objet);
			}

}
if(indice=="surface")
{
   var chaine=polyg;
var reg=new RegExp("[,]+", "g");
var tableau=chaine.split(reg);
for (var i=0; i<tableau.length-1; i++) 
{
 if(i==0)
 {polyg=tableau[i];
 }
 else
 {
polyg += ","+tableau[i]; 
 }
}

}
}

function coordonne(evt)
{
	root=svgdoc.getElementById("mapid");
	x=root.getAttributeNS(null,'x')
	y=root.getAttributeNS(null,'y')
	rectOveXcorner = parseFloat(svgRect.getAttributeNS(null,'x'));
	rectOveYcorner = parseFloat(svgRect.getAttributeNS(null,'y'));
	rectOvewidth = parseFloat(svgRect.getAttributeNS(null,'width'));
	rectOveheight = parseFloat(svgRect.getAttributeNS(null,'height'));
	xm=rectOveXcorner+((((evt.getClientX()-offsetXmap)/ratio)-x)*rectOvewidth/larmap);
	ym=rectOveYcorner+((((evt.getClientY()-offsetYmap)/ratio)-y)*rectOveheight/haumap);
}
function changecolor(x)
{
	selectstroke=svgdoc.getElementById("boutonprint").style.getPropertyValue("stroke");
	if(selectstroke!="0")
	{
	svgdoc.getElementById(selectid).setAttribute("stroke",selectstroke);
	
	selectid=x;
	}
	svgdoc.getElementById(x).setAttributeNS(null,"stroke","red");
}
function traitrouge(evt)
{
coordonne(evt)
debuttrait_x=xm;
debuttrait_y=ym;
if(lxdist!=0)
{
	debuttrait_x=lxdist;
	debuttrait_y=lydist;
}
ligne=svgDocument.createElementNS(null, "line");
ligne.setAttribute("id","traits2")
ligne.setAttribute("x1",(debuttrait_x))
ligne.setAttribute("y1",(debuttrait_y))
ligne.setAttribute("x2",(xm))
ligne.setAttribute("y2",(ym))
if(cotation!=0)
{
ligne.setAttribute("marker-start","url(#extremite_mesure)")
}
ligne.setAttribute("stroke-width","0.5")
ligne.setAttribute("fill", "red")
ligne.setAttribute("stroke","red")
svgDocument.getElementById("dess").appendChild(ligne)
}
function traitblue(evt)
{
	var indice1=countrait;
	if(indice=='surface')
	{
		var indice1=countsurface;
	}
	else if(indice=='cotation')
	{
		var indice1=countcotation;
	}
coordonne(evt)
ligne1=svgDocument.createElementNS(null, "line");
ligne1.setAttribute("id",indice+indice1)
ligne1.setAttribute("x1",lx)
ligne1.setAttribute("y1",ly)
ligne1.setAttribute("x2",xm)
ligne1.setAttribute("y2",ym)
if(cotation!=0)
{
ligne1.setAttribute("marker-start","url(#debut_mesure)")
ligne1.setAttribute("marker-end","url(#fin_mesure)")
}
ligne1.setAttribute("stroke-width","0.5")
ligne1.setAttribute("fill", "blue")
ligne1.setAttribute("stroke","blue")
svgDocument.getElementById("dess").appendChild(ligne1)
//if(nav!="0")
//{
varcotations[countcotation-1]=Math.round(lx*100)/100+"|"+Math.round(ly*100)/100+"|"+Math.round(xm*100)/100+"|"+Math.round(ym*100)/100+"|"+Math.round(angle*100)/100+"|"+Math.round(posix*100)/100+"|"+Math.round(posiy*100)/100+"|"+Math.round(longtrait*100)/100;
//}
}
function affichetext(evt)
{
	if(distance>0)
	{
		pox=xm;
		poy=ym;
		aff=cumul;
		var indice1=countrait;
	}
	else
	{
		pox=posix;
		poy=posiy;
		aff=longtrait;
		var indice1=countcotation;
	}
texte=svgDocument.createElementNS(null, 'text');
texte.setAttribute("fill","red");
texte.setAttribute("id","tex"+indice+indice1)
texte.setAttribute("font-size","3");
texte.setAttribute("x",pox);
texte.setAttribute("y",poy);
if(cotation!=0)
{
texte.setAttribute("transform","rotate("+angle+","+pox+","+poy+")");
texte.setAttribute("text-anchor","middle");
}
text_content = svgDocument.createTextNode(Math.round(aff*100)/100)
texte.appendChild(text_content)
svgDocument.getElementById("dess").appendChild(texte)
}
function validesurface(evt)
{
	if(polyg=="")
	{
		polyg="0 0,1 1";
	}
url="surface.php?polygo="+polyg+","+polyfin+"&sessionname=" + sessionname + "&sessionid=" + sessionid;
getURL(url,retoursurface)
}

function retoursurface(x)
{
message("la surface est de "+Math.floor(x.content*100)/100+" M2");	
}
function clearobj()
{
	
	if(countsurface>0)
	{
		for(var j=1;j<countsurface+1;j++)
			{
			objet=svgdoc.getElementById("surface"+j);
			svgdoc.getElementById("dess").removeChild(objet);
			}
			polygo="";
			if(polygon==1)
			{
			svgdoc.getElementById("effacepolygo").setAttribute("visibility","hidden");	
			svgdoc.getElementById("validepolygo").setAttribute("visibility","hidden");
			}
			else
			{
			svgdoc.getElementById("effacesurface").setAttribute("visibility","hidden");	
			svgdoc.getElementById("validesurface").setAttribute("visibility","hidden");
			}
			traitpret=0
			surface=1
			lxdist=0
			lx=0
			ly=0
			countsurface=0
	}

	if(countrait>0)
	{
		for(var j=1;j<countrait+1;j++)
	{
	objet=svgdoc.getElementById("distance"+j);
	svgdoc.getElementById("dess").removeChild(objet);
	objet=svgdoc.getElementById("tex"+indice+j);
	svgdoc.getElementById("dess").removeChild(objet)
	}
	objet=svgdoc.getElementById("traits2");
	traitpret=0
	distance=1
	lxdist=0
	lx=0
ly=0
countrait=0
	}
	
		if(svgdoc.getElementById("traits2"))
	{
	objet=svgdoc.getElementById("traits2");
	svgdoc.getElementById("dess").removeChild(objet);
	}
}
function activpoly(evt)
{
clearobj();	
svgdoc.getElementById("effacemesure").setAttribute("visibility","hidden");
svgdoc.getElementById("dessin").setAttribute("pointer-events","none");
svgdoc.getElementById("desrect").setAttribute("pointer-events","visible");
cotation=0;
distance=0;
surface=1;
polygon=1;
indice="surface";
polyg="";
traitpret=0;
}
function validepoly(evt)
{
	if(polyg=="")
	{
		polyg="0 0,1 1";
	}
var url="https://"+serveur+url_polygo+"?polygo="+polyg+","+polyfin+"&sessionname=" + sessionname + "&sessionid=" + sessionid;
var zer="livalidepolygo";
var param="_blank";
ouvrepage(url,param,zer)
clearobj()
}
function enregistre()
{
	image="";
	nom="";
	var ladate=new Date()
	var plus="M" + ladate.getMinutes() + "S" + ladate.getSeconds()
	nom=sessionid + plus;
	animation()
	var nb = layer.length;
	couche_svg="";
	for (b=0;b<nb;b++)
	{
	if(zlayer[layer[b]].svg_visible=='true')
		{
			if(couche_svg=="")
			{
			couche_svg +=layer[b];
			}
			else
			{
			couche_svg +=","+layer[b];
			}
			suppvarraster(layer[b])
		}
	}
	var reg=new RegExp("( )", "g");
	coucherasteraenvoyer=coucherastervisible.replace(reg,"_");
	coucherasteraenvoyer=coucherasteraenvoyer.substr(0,coucherasteraenvoyer.length-1)
	raster="";
	if(coucherasteraenvoyer!="")
							{
				image="ok";
				raster=	coucherasteraenvoyer;			
		}
	
	droite=svgdoc.getElementById("droite").getFirstChild().getData();
	centre=svgdoc.getElementById("centre").getFirstChild().getData();
	var reg=new RegExp("'", "g");
	par=placid;
	if(parce.replace(reg,"")!="")
	{
	par +=","+parce.replace(reg,"")
	}
	if(nav!=0)
	{
		var nb = varcotations.length;
		for(a=0;a<nb;a++)
		{
		getURL("cotation.php?eff=0&num="+a+"&cot="+varcotations[a],callback2);
		}
		if(nb==0)
		{
		getURL("cotation.php?eff=1",callback2);	
		}
		couche_svg=convasc(couche_svg);
		raster=convasc(raster);
		var url="enregistre.php?centre="+centre+"&droite="+droite+"&nom=" +nom+"&svg="+couche_svg+"&raster="+raster+"&x="+ xnulcorner + "&y=" + ynulcorner + "&lar=" + nWidth  + "&hau=" + nHeight+ "&zoom=" + zoomVal  + "&xini=" + xini + "&yini=" + yini +"&nav="+nav+"&parce="+par+"&sessionname=";
		
	}
	else
	{
	var nb = varcotations.length;
		for(a=0;a<nb;a++)
		{
		getURL("cotation.php?eff=0&num="+a+"&cot="+varcotations[a],callback2);
		}
		if(nb==0)
		{
		getURL("cotation.php?eff=1",callback2);	
		}	

var url="enregistre.php?centre="+centre+"&droite="+droite+"&nom=" +nom+"&svg="+couche_svg+"&raster="+raster+"&x="+ xnulcorner + "&y=" + ynulcorner + "&lar=" + nWidth  + "&hau=" + nHeight+ "&zoom=" + zoomVal  + "&xini=" + xini + "&yini=" + yini +"&nav="+nav+"&parce="+par+"&sessionname=";
		}
		setTimeout("cache()",6000);
		var zer="lienre";
var param="";
ouvrepage(url,param,zer)
	
}
function callback2(data)
{
if (!data.success) 
	message("nok")
}
function callback(data)
{
if (data.success){
cache();
}
else
{message('enregistrement imposssible')
}

}
function construction_svg()
{
var str="";	
str +=printNode(svgdoc.getElementById("dess"));
return str
}
function aide()
{
var url="https://" + serveur + "/interface/Manuel.pdf";
var param="_blank";
var zer="aide";	
ouvrepage(url,param,zer)	
}
function contacte()
{
var url="https://" + serveur + "/interface/mail/mailto.php";
var param="_blank";
var zer="contacte";	
ouvrepage(url,param,zer)	
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
