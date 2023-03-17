CREATE TABLE odermangroup_dev_2023.academico_matriculas_cursos (
	matcur_id varchar(45) NOT NULL PRIMARY KEY,
	matcur_id_matricula int(10) unsigned NOT NULL,
	matcur_id_curso int(10) unsigned NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci
COMMENT='Guardará los cursos adicionales de cada estudiante.';

ALTER TABLE odermangroup_dev_2023.academico_matriculas_cursos ADD CONSTRAINT academico_matriculas_cursos_FK FOREIGN KEY (matcur_id_curso) REFERENCES odermangroup_dev_2023.academico_grados(gra_id);
