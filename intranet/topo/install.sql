CREATE SEQUENCE public.geometre_gid_seq;

CREATE SEQUENCE public.geomet_id;

CREATE TABLE public.geometre ( 
    gid       	int4 NOT NULL DEFAULT nextval('geometre_gid_seq'::regclass),
    id        	varchar NULL DEFAULT currval(('geomet_id'::text)::regclass),
    name      	varchar NULL,
    url       	varchar NULL,
    the_geom  	geometry NULL,
    code_insee	varchar NULL DEFAULT 770284,
    PRIMARY KEY(gid)
);

ALTER TABLE public.geometre
    ADD CONSTRAINT enforce_srid_the_geom
	CHECK (srid(the_geom) = -1);

ALTER TABLE public.geometre
    ADD CONSTRAINT enforce_dims_the_geom
	CHECK (ndims(the_geom) = 2);

CREATE TABLE public.geometre_ssql ( 
    boite   	varchar(2) NULL,
    disquet 	varchar(5) NULL,
    fichier 	varchar(8) NULL,
    spa_id  	varchar(10) NULL DEFAULT nextval(('geomet_id'::text)::regclass),
    ll_x    	float8 NULL,
    ll_y    	float8 NULL,
    ur_x    	float8 NULL,
    ur_y    	float8 NULL,
    lab_x   	float8 NULL,
    lab_y   	float8 NULL,
    rot_lab 	float8 NULL,
    service 	varchar(10) NULL,
    local1  	varchar(8) NULL,
    dat     	varchar NULL,
    ass     	varchar(1) NULL,
    aep     	varchar(1) NULL,
    ep      	varchar(1) NULL,
    recol   	varchar(1) NULL,
    geometre	varchar(10) NULL 
    );

INSERT INTO admin_svg.application(libelle_appli, btn_polygo, libelle_btn_polygo, divers, zoom_ouverture, zoom_min, zoom_max, url) 
    VALUES("Plan géomètre","/topo/plans.php","extraction-insertion plan topo","",600,200,5000,"interface/carto.php");

INSERT INTO admin_svg.theme(libelle_them, schema, tabl, raster, partiel, vu_initial, couch_tjs_visible, zoommin, zoommax, zoommax_raster, vu_anonyme, groupe) 
    VALUES("Plan geometre","public","geomet","","","","",200,5000,"0",false,(null))

INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),"id","ident","1")
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),"fichier","ad","2")
INSERT INTO admin_svg.col_sel(idtheme, appel, nom_as, ordre_sel) 
    VALUES(currval('admin_svg.them'::text),"the_geom","geom","3")

INSERT INTO admin_svg.style(idtheme, idutilisateur, fill, stroke_rgb, stroke_width, font_familly, font_size, font_weight, symbole, id_symbole, opacity, fill_rule, stroke_dasharray, stroke_dashoffset) 
    VALUES(currval('admin_svg.them'::text),(null),"100,200,150","none","","1","","","","","0.4","","","")

INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text), currval('admin_svg.them'::text),"sur(evt,'red','*Cliquez pour obtenir le plan geometre $ ','*Cliquez pour s'+String.fromCharCode(233)+'lectionner le(es) contour(s) $  puis sur le bouton valider pour obtenir la liste des plans')","lien(evt,'geom','http://'+serveur+'/topo/topo.php?obj_keys=','m','')","hors(evt)",3,0,0,"visible",false,200,5000,(null),false,false,false,(null),(null))
    
INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='batiment')::text,"","","",2,0,0,"",true,200,5000,(null),true,false,false,(null),(null))

INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='parcelle')::text,"","","",1,0,0,"none",true,100,5000,600,false,true,true,"1","1")

INSERT INTO admin_svg.appthe(idapplication, idtheme, mouseover, click, mouseout, ordre, sordre, ordre_couche, pointer_events, raster, zoommin, zoommax, zoommaxraster, objselection, objprincipal, objrecherche, partiel, vu_initial) 
    VALUES(currval('admin_svg.appli'::text),(select idtheme from admin_svg.theme where libelle_them='batiment')::text,"","","",4,0,0,"",true,200,5000,(null),false,false,false,(null),(null))


