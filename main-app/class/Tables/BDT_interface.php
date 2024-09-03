<?php

interface BDT_Interface {
    public static function getTableName();

    public static function Select(Array $predicado = [], $campos = '*');

    public static function Insert(Array $datos);

    public static function Update(Array $datos, Array $predicado);

    public static function Delete(Array $predicado);

    public static function InsertOrUpdate(Array $datos, Array $predicado);

    public static function deleteBeforeInsert(Array $datos, array $predicado);

    public static function numRows(Array $predicado = []);
}