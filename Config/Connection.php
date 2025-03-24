<?php
namespace Config;

use Exception;
use Errors\Error;
use PDO,PDOException;

/**
 * 
 * TODO: Clase para conectarse a la base de datos.
 * NOTA: Nadie podrá heredar esta clase
 */
final class Connection
{
    // Atributos para hacer la conexión
    private static $userDatabase;
    private static $passwordUserDataBase;
    private static $serverName;
    private static $nameDataBase;
    private static $url;
    // Instancia de la misma clase actual.
    private static $objConnection = null;
    // Instancia que me dará el PDO.
    private static $objPDO = null;

    /**
     * TODO: Constructor privado donde nadie podrá crear una nueva instancia
     */
    private function __construct()
    {
        // Bloque try-catch
        try {
            self::$userDatabase=$_ENV['DATABASE_SER'];
            self::$passwordUserDataBase=$_ENV['DATABASE_PASSWORD'];
            self::$serverName=$_ENV['DATABASE_SERVERNAME'];
            self::$nameDataBase=$_ENV['DATABASE_NAME'];
            // Me regresa un objeto de la clase PDO para que yo pueda hacer las operaciones CRUD
            self::$objPDO = self::connectDatabase();
            // Captura el error por si hay un problema.
        } catch (PDOException $eror) {
            throw new Exception( Error::$tipoError['noConeccionBD']['mensaje'], Error::$tipoError['noConeccionBD']['code'] );
            exit(1);
        }
    }


    /**
     * TODO: Método que nos hace la conexión a la base de datos.
     *
     * @return PDO
     */
    public static function connectDatabase(): PDO
    {
        // Preguntamos si el objetoPDO si es null
        if (self::$objPDO === null) {
            self::$url = "mysql:host=" . self::$serverName . ";dbname=" . self::$nameDataBase;
            self::$objPDO = new PDO(self::$url, self::$userDatabase, self::$passwordUserDataBase);
            self::$objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establecer el modo de error.
        }
        return self::$objPDO;
    }

    /**
     * TODO: Método para obtener 
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
