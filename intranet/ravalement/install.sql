CREATE TABLE geotest.photo_ravalement
(
  id_photo serial,
  id_ravalement integer
)
WITH (OIDS=TRUE);


CREATE TABLE geotest.ravalement
(
  gid serial,
  etat character varying,
  date character varying,
  observation character varying,
  photo character varying,
  propriet character varying,
  the_geom geometry,
  code_insee character varying,
  surveiller character varying
)
WITH (OIDS=TRUE);

INSERT INTO geotest.ravalement(etat,the_geom) values('a',(null));
INSERT INTO geotest.ravalement(etat,the_geom) values('b',(null));
INSERT INTO geotest.ravalement(etat,the_geom) values('c',(null));

INSERT INTO admin_svg.application(libelle_appli, btn_polygo, libelle_btn_polygo, divers, zoom_ouverture, zoom_min, zoom_max, url) 
    VALUES('Ravalement','/ravalement/creation.php','Sélection du batiment',(null),600,100,10000,'interface/carto.php');
	
INSERT INTO admin_svg.theme(libelle_them, schema, tabl, raster, partiel, vu_initial, couch_tjs_visible, zoommin, zoommax, zoommax_raster, vu_anonyme, groupe) 
    VALUES('Etat des facades','geotest','ravalement',(null),(null),(null),(null),100,10000,'0',false,(null));
	
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),'gid','ident',(null));
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),'gid','ad',(null));
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),'the_geom','geom',(null));	
	
	
INSERT INTO admin_svg.style(idtheme, idutilisateur, fill, stroke_rgb, stroke_width, font_familly, font_size, font_weight, symbole, id_symbole, opacity, fill_rule, stroke_dasharray, stroke_dashoffset) 
    VALUES(currval('admin_svg.them'::text),(null),'0,0,0','0,0,0','1',(null),(null),(null),(null),(null),'1',(null),(null),(null));


INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text), currval('admin_svg.them'::text),'sur(evt,\'rgb(85,198,78)\')','lien(evt,\'facade\',\'http://\'+serveur+\'/ravalement/interro.php?obj_keys=\',\'\',\'\')','hors(evt)',3,(null),(null),'visible',true,100,10000,0,true,false,false,'0','0');
	
INSERT INTO admin_svg.col_theme(idappthe,colonn,intitule_legende,valeur_mini,valeur_maxi,valeur_texte,fill,stroke_rgb,stroke_width,font_familly,font_size,font_weight,symbole,id_symbole,opacity,fill_rule,stroke_dasharray,stroke_dashoffset,ordre) values (currval('admin_svg.appth'::text),'etat','bon etat',(null),(null),'a','0,112,0','none','1',(null),(null),(null),(null),(null),'0.85',(null),(null),(null),1);

INSERT INTO admin_svg.col_theme(idappthe,colonn,intitule_legende,valeur_mini,valeur_maxi,valeur_texte,fill,stroke_rgb,stroke_width,font_familly,font_size,font_weight,symbole,id_symbole,opacity,fill_rule,stroke_dasharray,stroke_dashoffset,ordre) values (currval('admin_svg.appth'::text),'etat','etat moyen',(null),(null),'b','255,88,0','none','1',(null),(null),(null),(null),(null),'0.85',(null),(null),(null),2);

INSERT INTO admin_svg.col_theme(idappthe,colonn,intitule_legende,valeur_mini,valeur_maxi,valeur_texte,fill,stroke_rgb,stroke_width,font_familly,font_size,font_weight,symbole,id_symbole,opacity,fill_rule,stroke_dasharray,stroke_dashoffset,ordre) values (currval('admin_svg.appth'::text),'etat','mauvais etat',(null),(null),'c','126,64,0','none','1',(null),(null),(null),(null),(null),'0.85',(null),(null),(null),1);


INSERT INTO admin_svg.theme(libelle_them, schema, tabl, raster, partiel, vu_initial, couch_tjs_visible, zoommin, zoommax, zoommax_raster, vu_anonyme, groupe) 
    VALUES('Facade a surveiller','geotest','ravalement',(null),(null),(null),(null),100,10000,'0',false,(null));
	
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),'gid','ident',(null));
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),'gid','ad',(null));
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),'the_geom','geom',(null));	

INSERT INTO admin_svg.col_where(idtheme, clause) 
    VALUES(currval('admin_svg.them'::text),'surveiller=\'1\'');	
	
INSERT INTO admin_svg.style(idtheme, idutilisateur, fill, stroke_rgb, stroke_width, font_familly, font_size, font_weight, symbole, id_symbole, opacity, fill_rule, stroke_dasharray, stroke_dashoffset) 
    VALUES(currval('admin_svg.them'::text),(null),'0,0,255','0,0,0','1',(null),(null),(null),(null),(null),'0.6',(null),(null),(null));


INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text), currval('admin_svg.them'::text),(null),(null),(null),2,(null),(null),'none',true,100,10000,0,true,false,false,'0','0');  
	
INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='parcelle')::text,(null),(null),(null),1,(null),(null),'none',true,100,10000,1000,false,true,false,'1','1'); 
	
INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='num_voie')::text,(null),(null),(null),5,(null),(null),'none',true,2000,10000,10000,false,false,false,'1','0');
	
INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='nom_voie')::text,(null),(null),(null),6,(null),(null),'none',true,2000,10000,10000,false,false,false,'1','0');
	
INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='batiment')::text,(null),(null),(null),4,(null),(null),'none',true,100,10000,10000,false,false,false,'1','0');
	
INSERT INTO admin_svg.col_theme(idappthe,colonn,intitule_legende,valeur_mini,valeur_maxi,valeur_texte,fill,stroke_rgb,stroke_width,font_familly,font_size,font_weight,symbole,id_symbole,opacity,fill_rule,stroke_dasharray,stroke_dashoffset,ordre) values (currval('admin_svg.appth'::text),'constructi','bati leger',(null),(null),'bati leger','227,132,183','none','1',(null),(null),(null),(null),(null),'1',(null),(null),(null),2);

INSERT INTO admin_svg.col_theme(idappthe,colonn,intitule_legende,valeur_mini,valeur_maxi,valeur_texte,fill,stroke_rgb,stroke_width,font_familly,font_size,font_weight,symbole,id_symbole,opacity,fill_rule,stroke_dasharray,stroke_dashoffset,ordre) values (currval('admin_svg.appth'::text),'constructi','bati dur',(null),(null),'bati dur','183,100,150','none','1',(null),(null),(null),(null),(null),'1',(null),(null),(null),1);
	