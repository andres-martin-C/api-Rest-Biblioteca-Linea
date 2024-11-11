<?php

/**
 * 
 * TODO: Clase para conectarse a la base de datos.
 * NOTA: Nadie podrá heredar esta clase
 */
final class connection
{
    // Atributos para hacer la conexión
    private static $userDatabase = "root";
    private static $passwordUserDataBase = "";
    private static $serverName = "localhost";
    private static $nameDataBase = "prueba";
    private static $url;
    // Instancia de la misma clase actual.
    private static $objConnection = null;
    private static $contador = 0;
    // Instancia que me dará el PDO.
    public $objPDO = null;

    /**
     * TODO: Constructor privado donde nadie podrá crear una nueva instancia
     */
    private function __construct()
    {
        // Bloque try-catch
        try {
            // Me regresa un objeto de la clase PDO para que yo pueda hacer las operaciones CRUD
            $this->objPDO = $this->connectDatabase();
            // Captura el error por si hay un problema.
        } catch (PDOException $eror) {
            echo 'No hizo la conexión';
            exit(1);
        }
    }


    /**
     * TODO: Método que nos hace la conexión a la base de datos.
     *
     * @return PDO
     */
    private function connectDatabase(): PDO
    {
        // Preguntamos si el objetoPDO si es null
        if ($this->objPDO === null) {
            self::$url = "mysql:host=" . self::$serverName . ";dbname=" . self::$nameDataBase;
            $this->objPDO = new PDO(self::$url, self::$userDatabase, self::$passwordUserDataBase);
            $this->objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establecer el modo de error.
        }
        return $this->objPDO;
    }

    /**
     * Undocumented function
     *
     * @return connection
     */
    public static function instanceObject(): connection
    {
        if (self::$objConnection == null) {
            self::$objConnection = new self();
        }
        return self::$objConnection;
    }
}
