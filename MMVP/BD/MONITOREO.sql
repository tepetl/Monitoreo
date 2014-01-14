CREATE TABLE estacion_teo(
id int4,
fecha_hora timestamp,
dia int4,
regisrtro float4);


CREATE INDEX id_estacion_teo ON estacion_teo (id);

-- DROP TABLE estacion_chx;
CREATE TABLE estacion_chx(
id int4,
fecha_hora timestamp,
dia int4,
registro float4);


CREATE INDEX id_estacion_chx ON estacion_chx (id);

GRANT ALL ON estacion_chx TO popoca;




CREATE TABLE estacion_tla(
id int(4),
fecha_hora timestamp,
dia int(4),
registro float4);

CREATE INDEX id_estacion_tla ON estacion_tla (id);


CREATE TABLE resultados(
id    int8,
alfa  float4,
teo   float4,
tla   float4,
delta float4,
fecha timestamp);

CREATE INDEX id_resultados ON resultados (id);
