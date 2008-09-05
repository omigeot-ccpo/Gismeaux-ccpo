create table cadastre.batiment08 as select * from cadastre.batiment;
delete from cadastre.batiment;
select setval('cadastre.batiment2_gid_seq',1);
reindex table cadastre.batiment;
insert into cadastre.batiment (constructi,code_insee,the_geom) select case dur when '01' then 'Bati dur' when '02' then 'Bati leger' end, code_insee, the_geom from test_cadastre.bati;

create table cadastre.lieudit08 as select * from cadastre.lieudit;
delete from cadastre.lieudit;
select setval('cadastre.lieudit_gid_seq',1);
reindex table cadastre.lieudit;
insert into cadastre.lieudit (texte,texte_2,texte_3,texte_4,texte_5,texte_6,texte_7,texte_8,texte_9,texte_10,the_geom,code_insee) select tex, tex2, tex3, tex4, tex5, tex6, tex7, tex8, tex9, tex10, the_geom, code_insee from test_cadastre.lieu_dit;

create table cadastre.numvoie08 as select * from cadastre.numvoie;
delete from cadastre.numvoie;
reindex table cadastre.numvoie;
insert into cadastre.numvoie (texte,code_insee,the_geom) select tex, code_insee, the_geom from test_cadastre.num_voie;

create table cadastre.parcelle08 as select * from cadastre.parcelle;
delete from cadastre.parcelle;
reindex table cadastre.parcelle;
select setval('cadastre.parcelle_gid_seq',1);
insert into cadastre.parcelle (arpentage, identifian, figuration, majic2, texte_2, texte, code_insee, the_geom) select coar, idu, case indp when '01' then 'Parcelle figuree au plan' when '02' then 'Parcelle non figuree au plan' end, supf, tex2, tex, code_insee, the_geom from test_cadastre.parc;
delete from cadastre.parcelle where parcelle.gid < any(select b.gid from cadastre.parcelle as b where parcelle.gid <> b.gid and parcelle.identifian=b.identifian);

create table cadastre.nomvoie08 as select * from cadastre.nomvoie;
delete from cadastre.nomvoie;
reindex table cadastre.nomvoie;
insert into cadastre.nomvoie select tex10,tex2, tex3, tex4, tex5, tex6, tex7, tex8, tex9 ,tex, code_insee, the_geom from test_cadastre.zone_communic;
