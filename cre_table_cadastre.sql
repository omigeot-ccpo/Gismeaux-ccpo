SET client_encoding = 'LATIN9';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = cadastre, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: batiment; Type: TABLE; Schema: cadastre; Owner: sig; Tablespace: 
--

CREATE TABLE batiment (
    gid serial NOT NULL,
    constructi character varying,
    code_insee character varying DEFAULT 770475,
    the_geom public.geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);


ALTER TABLE cadastre.batiment OWNER TO sig;

--
-- Name: batiment2_pkey; Type: CONSTRAINT; Schema: cadastre; Owner: sig; Tablespace: 
--

ALTER TABLE ONLY batiment
    ADD CONSTRAINT batiment2_pkey PRIMARY KEY (gid);


ALTER INDEX cadastre.batiment2_pkey OWNER TO sig;

-- Name: lieudit; Type: TABLE; Schema: cadastre; Owner: postgres; Tablespace: 
--

CREATE TABLE lieudit (
    gid serial NOT NULL,
    texte character varying,
    texte_2 character varying,
    texte_3 character varying,
    texte_4 character varying,
    texte_5 character varying,
    texte_6 character varying,
    texte_7 character varying,
    texte_8 character varying,
    texte_9 character varying,
    texte_10 character varying,
    the_geom public.geometry,
    code_insee character varying,
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);


ALTER TABLE cadastre.lieudit OWNER TO postgres;

--
-- Name: lieudit_pkey; Type: CONSTRAINT; Schema: cadastre; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lieudit
    ADD CONSTRAINT lieudit_pkey PRIMARY KEY (gid);


ALTER INDEX cadastre.lieudit_pkey OWNER TO postgres;

-- Name: nomvoie; Type: TABLE; Schema: cadastre; Owner: postgres; Tablespace: 
--

CREATE TABLE nomvoie (
    texte10 character(80),
    texte2 character(80),
    texte3 character(80),
    texte4 character(80),
    texte5 character(80),
    texte6 character(80),
    texte7 character(80),
    texte8 character(80),
    texte9 character(80),
    texte character(80),
    code_insee character varying(6),
    the_geom public.geometry
);


ALTER TABLE cadastre.nomvoie OWNER TO postgres;

-- Name: numvoie; Type: TABLE; Schema: cadastre; Owner: postgres; Tablespace: 
--

CREATE TABLE numvoie (
    texte character varying(80),
    code_insee character varying(6),
    the_geom public.geometry,
    gid integer DEFAULT nextval('cadastre.numvoie_gid_seq'::text)
);


ALTER TABLE cadastre.numvoie OWNER TO postgres;

--
-- Name: parcelle; Type: TABLE; Schema: cadastre; Owner: sig; Tablespace: 
--

CREATE TABLE parcelle (
    gid serial NOT NULL,
    arpentage character varying,
    parcelle character varying,
    identifian character varying,
    figuration character varying,
    majic2 numeric,
    texte_2 character varying,
    texte character varying,
    code_insee character varying DEFAULT 770475,
    the_geom public.geometry,
    ordre_affichage integer DEFAULT 0,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.srid(the_geom) = -1))
);


ALTER TABLE cadastre.parcelle OWNER TO sig;

--
-- Name: parcelle2_pkey; Type: CONSTRAINT; Schema: cadastre; Owner: sig; Tablespace: 
--

ALTER TABLE ONLY parcelle
    ADD CONSTRAINT parcelle2_pkey PRIMARY KEY (gid);


ALTER INDEX cadastre.parcelle2_pkey OWNER TO sig;

