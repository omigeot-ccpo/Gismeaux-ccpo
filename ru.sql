SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;
create schema geotest
SET search_path = geotest, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = true;

CREATE TABLE alignement (
    gid serial NOT NULL,
    the_geom public.geometry,
    date_ali text,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTILINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.alignement OWNER TO sig;
COMMENT ON COLUMN alignement.date_ali IS 'format jj/mm/yyyy';
ALTER TABLE ONLY alignement
    ADD CONSTRAINT alignement_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.alignement_pkey OWNER TO sig;


CREATE TABLE cimetiere (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.cimetiere OWNER TO sig;
ALTER TABLE ONLY cimetiere
    ADD CONSTRAINT cimetiere_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.cimetiere_pkey OWNER TO sig;



CREATE TABLE emplreserve (
    gid serial NOT NULL,
    "desc" character varying,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.emplreserve OWNER TO sig;
COMMENT ON COLUMN emplreserve."desc" IS 'description';
ALTER TABLE ONLY emplreserve
    ADD CONSTRAINT emplreserve_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.emplreserve_pkey OWNER TO sig;



CREATE TABLE halage (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying DEFAULT 770475,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTILINESTRING'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.halage OWNER TO sig;
ALTER TABLE ONLY halage
    ADD CONSTRAINT halage_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.halage_pkey OWNER TO sig;



CREATE TABLE ht_fuseau (
    id_fuseau integer DEFAULT nextval('geotest.ht_fuseau_id'::text),
    gid1 integer,
    gid2 integer,
    distance real,
    hauteur real,
    code_insee character varying
);
ALTER TABLE geotest.ht_fuseau OWNER TO postgres;
COMMENT ON COLUMN ht_fuseau.gid1 IS 'point de départ';
COMMENT ON COLUMN ht_fuseau.gid2 IS 'point de fin';


CREATE TABLE ht_ligne (
    id_ligne integer DEFAULT nextval('geotest.ht_ligne_id'::text),
    libelle character varying
);
ALTER TABLE geotest.ht_ligne OWNER TO postgres;



CREATE TABLE ht_pylone (
    id_ligne integer DEFAULT 1,
    gid integer DEFAULT nextval('geotest.ht_pylone_gid'::text),
    the_geom public.geometry,
    "type" character varying DEFAULT 'pylone'::character varying,
    "precision" real DEFAULT 5
);
ALTER TABLE geotest.ht_pylone OWNER TO postgres;



CREATE TABLE lisiere (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.lisiere OWNER TO sig;
ALTER TABLE ONLY lisiere
    ADD CONSTRAINT lisiere_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.lisiere_pkey OWNER TO sig;



CREATE TABLE lotissement (
    gid serial NOT NULL,
    nom character varying,
    date character varying,
    numarrete character varying,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.lotissement OWNER TO sig;
COMMENT ON COLUMN lotissement.nom IS 'nom du lotissement';
COMMENT ON COLUMN lotissement.date IS 'format jj/mm/yyyy';
COMMENT ON COLUMN lotissement.numarrete IS 'numero arrete';
ALTER TABLE ONLY lotissement
    ADD CONSTRAINT lotissement_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.lotissement_pkey OWNER TO sig;



CREATE TABLE ppmh (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.ppmh OWNER TO sig;
ALTER TABLE ONLY ppmh
    ADD CONSTRAINT ppmh_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.ppmh_pkey OWNER TO sig;



CREATE TABLE ppri (
    gid serial NOT NULL,
    ppri_id integer,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.ppri OWNER TO sig;
ALTER TABLE ONLY ppri
    ADD CONSTRAINT ppri_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.ppri_pkey OWNER TO sig;



CREATE TABLE servradioelectrik (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.servradioelectrik OWNER TO sig;
ALTER TABLE ONLY servradioelectrik
    ADD CONSTRAINT servradioelectrik_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.servradioelectrik_pkey OWNER TO sig;



CREATE TABLE sidobre (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.sidobre OWNER TO sig;
ALTER TABLE ONLY sidobre
    ADD CONSTRAINT sidobre_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.sidobre_pkey OWNER TO sig;


CREATE TABLE siteinscrit (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.siteinscrit OWNER TO sig;
ALTER TABLE ONLY siteinscrit
    ADD CONSTRAINT siteinscrit_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.siteinscrit_pkey OWNER TO sig;



CREATE TABLE z_archeo (
    gid serial NOT NULL,
    code bigint,
    the_geom public.geometry,
    code_insee character varying,
    nom character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.z_archeo OWNER TO sig;
COMMENT ON COLUMN z_archeo.code IS 'code zone';
COMMENT ON COLUMN z_archeo.nom IS 'niveau archeo';
ALTER TABLE ONLY z_archeo
    ADD CONSTRAINT z_archeo_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.z_archeo_pkey OWNER TO sig;



CREATE TABLE zac (
    gid serial NOT NULL,
    
the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.zac OWNER TO sig;
ALTER TABLE ONLY zac
    ADD CONSTRAINT zac_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.zac_pkey OWNER TO sig;



CREATE TABLE zonage (
    gid integer DEFAULT nextval('geotest.zonage_gid'::text),
    "zone" character varying,
    the_geom public.geometry,
    code_insee character varying DEFAULT 770475
);
ALTER TABLE geotest.zonage OWNER TO sig;
COMMENT ON COLUMN zonage."zone" IS 'reference zone';




CREATE TABLE zoneboise (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.zoneboise OWNER TO sig;
ALTER TABLE ONLY zoneboise
    ADD CONSTRAINT zoneboise_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.zoneboise_pkey OWNER TO sig;



CREATE TABLE zonebruit (
    gid serial NOT NULL,
    "type" character varying,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.zonebruit OWNER TO sig;
COMMENT ON COLUMN zonebruit."type" IS 'type zone de bruit';
ALTER TABLE ONLY zonebruit
    ADD CONSTRAINT zonebruit_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.zonebruit_pkey OWNER TO sig;




CREATE TABLE zoneinondable (
    gid serial NOT NULL,
    "zone" character varying,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.zoneinondable OWNER TO sig;
COMMENT ON COLUMN zoneinondable."zone" IS 'reference zone';
ALTER TABLE ONLY zoneinondable
    ADD CONSTRAINT zoneinondable_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.zoneinondable_pkey OWNER TO sig;



CREATE TABLE zononaedificandi (
    gid serial NOT NULL,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);
ALTER TABLE geotest.zononaedificandi OWNER TO sig;
ALTER TABLE ONLY zononaedificandi
    ADD CONSTRAINT zononaedificandi_pkey PRIMARY KEY (gid);
ALTER INDEX geotest.zononaedificandi_pkey OWNER TO sig;



CREATE VIEW ligne_ht AS SELECT ht_fuseau.id_fuseau AS gid, buffer(makeline((( SELECT astext(ht_pylone.the_geom) AS astext FROM ht_pylone WHERE ht_pylone.gid = ht_fuseau.gid1))::geometry, (( SELECT astext(ht_pylone.the_geom) AS astext FROM ht_pylone WHERE ht_pylone.gid = ht_fuseau.gid2))::geometry), (ht_fuseau.distance + (( SELECT max(ht_pylone."precision") AS max FROM ht_pylone WHERE ht_pylone.gid = ht_fuseau.gid1 OR ht_pylone.gid = ht_fuseau.gid2)))::double precision) AS the_geom,ht_fuseau.code_insee FROM ht_fuseau;
