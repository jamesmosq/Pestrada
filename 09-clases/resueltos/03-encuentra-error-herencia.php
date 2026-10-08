<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — La nómina que paga mal
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Un centro de formación paga a sus empleados así:
 *    - Todo Empleado tiene nombre y salario base. Su salario = salario base.
 *    - Un Instructor ES un Empleado que además recibe $ 40.000 por cada hora
 *      de formación. Su salario = salario base + horas x 40.000.
 *    - La ficha de cada uno se muestra como "Nombre — $ salario".
 *
 *  El código tiene CUATRO errores. PHP muestra algunos avisos (Warning),
 *  pero no todos los errores producen avisos.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. Ejecútalo y mira qué pruebas dicen FALLA (y qué avisos aparecen).
 *  2. Encuentra cada error y explica POR QUÉ ocurre.
 *  3. Corrígelo aquí mismo hasta que todas las pruebas digan OK.
 *  4. Al final, compara con la versión corregida.
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: ¿quién le asigna el nombre a un Instructor?
 *    - Pista 2: ¿una clase hija puede ver lo que el padre declaró private?
 *    - Pista 3: dentro de un método, ¿$nombre es lo mismo que $this->nombre?
 *    - Pista 4: el salario del instructor ¿incluye el salario base?
 */

class Empleado
{
    protected $nombre;
    private $salarioBase;

    public function __construct($nombre, $salarioBase)
    {
        $this->nombre = $nombre;
        $this->salarioBase = $salarioBase;
    }

    public function calcularSalario()
    {
        return $this->salarioBase;
    }

    public function ficha()
    {
        return $nombre . " — $ " . number_format($this->calcularSalario(), 0, ',', '.');
    }
}

class Instructor extends Empleado
{
    private $horas;

    public function __construct($nombre, $salarioBase, $horas)
    {
        $this->horas = $horas;
    }

    public function calcularSalario()
    {
        return $this->horas * 40000;
    }

    public function salarioBaseMasUnMillon()
    {
        return $this->salarioBase + 1000000;
    }
}

// ── Pruebas automáticas: NO las modifiques, corrige las clases ────────────────
$empleado   = new Empleado('Rosa', 1500000);
$instructor = new Instructor('James', 2000000, 20);

$pruebas = [
    ['salario del empleado',            $empleado->calcularSalario(),            1500000],
    ['salario del instructor',          $instructor->calcularSalario(),          2800000],
    ['ficha del empleado',              $empleado->ficha(),                      'Rosa — $ 1.500.000'],
    ['ficha del instructor',            $instructor->ficha(),                    'James — $ 2.800.000'],
    ['salario base + 1 millón',         $instructor->salarioBaseMasUnMillon(),   3000000],
];

foreach ($pruebas as [$descripcion, $obtenido, $esperado]) {
    $ok = $obtenido == $esperado;
    echo ($ok ? "OK    " : "FALLA ") . " $descripcion: esperado " . var_export($esperado, true)
       . ", obtenido " . var_export($obtenido, true) . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - En Python, si la hija define __init__, también tiene que llamar
 *    super().__init__(...) o el padre no se inicializa. En PHP es igual,
 *    con otra sintaxis: parent::__construct(...).
 *  - Python no tiene private real; PHP sí, y una clase HIJA tampoco ve lo
 *    private del padre. Para compartirlo con las hijas se usa protected.
 *  - En Python, olvidar "self." da error inmediato (NameError). En PHP,
 *    olvidar "$this->" solo da un Warning y el valor queda en null.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué Instructor::ficha() funciona si Instructor no tiene un método
 *     ficha()? ¿Y por qué, dentro de ficha(), se ejecuta el calcularSalario()
 *     del Instructor y no el del Empleado?
 *  2. ¿Cuándo usarías private y cuándo protected?
 *  3. ¿Qué cambiaría si declaras las propiedades con tipo (protected string $nombre)?
 *     Pista: hoy, si nadie le asigna el nombre al Instructor, pasa en silencio (queda vacío).
 *     Con tipo, PHP lanza "must not be accessed before initialization" y detiene todo.
 *     ¿Qué es mejor?
 *
 *
 *
 *
 *
 *
 *
 *
 *  (sigue bajando solo cuando termines)
 *
 *
 *
 *
 *
 *
 *
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  VERSIÓN CORREGIDA
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  class Empleado
 *  {
 *      protected $nombre;
 *      protected $salarioBase;                    // ERROR 2: era private; la hija no lo veía
 *
 *      public function __construct($nombre, $salarioBase)
 *      {
 *          $this->nombre = $nombre;
 *          $this->salarioBase = $salarioBase;
 *      }
 *
 *      public function calcularSalario()
 *      {
 *          return $this->salarioBase;
 *      }
 *
 *      public function ficha()
 *      {
 *          return $this->nombre . " — $ " . number_format($this->calcularSalario(), 0, ',', '.');
 *                                                 // ERROR 3: faltaba $this-> en $nombre
 *      }
 *  }
 *
 *  class Instructor extends Empleado
 *  {
 *      private $horas;
 *
 *      public function __construct($nombre, $salarioBase, $horas)
 *      {
 *          parent::__construct($nombre, $salarioBase);   // ERROR 1: nadie inicializaba nombre y salario
 *          $this->horas = $horas;
 *      }
 *
 *      public function calcularSalario()
 *      {
 *          return parent::calcularSalario() + $this->horas * 40000;
 *                                                 // ERROR 4: faltaba sumar el salario base
 *      }
 *
 *      public function salarioBaseMasUnMillon()
 *      {
 *          return $this->salarioBase + 1000000;
 *      }
 *  }
 *
 *  Respuesta a la pregunta 1: ficha() se HEREDA de Empleado. Y como $this es
 *  un Instructor, $this->calcularSalario() ejecuta la versión del Instructor.
 *  Eso es polimorfismo: el mismo llamado se comporta según el objeto real.
 */
