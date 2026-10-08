<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — ¿Copia o el mismo objeto?
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Lee cada caso y escribe en tu cuaderno qué crees que imprime.
 *  3. Ejecútalo y compara. Lee la explicación de los que fallaste.
 */

class Cuenta
{
    public static int $cuentasCreadas = 0;   // de la CLASE (una sola para todas)
    public float $saldo;                     // de cada OBJETO

    public function __construct(public string $titular, float $saldo = 0)
    {
        $this->saldo = $saldo;
        self::$cuentasCreadas++;
    }

    public function consignar(float $monto): static
    {
        $this->saldo += $monto;
        return $this;                        // devuelve el mismo objeto
    }
}

// ── CASO 1 ────────────────────────────────────────────────────────────────────
$a = new Cuenta('Ana', 100);
$b = $a;
$b->saldo = 500;
echo "Caso 1: a = {$a->saldo}, b = {$b->saldo}<br>";
// Tu predicción: ____________


// ── CASO 2 ────────────────────────────────────────────────────────────────────
$c = new Cuenta('Carlos', 100);
$d = clone $c;
$d->saldo = 500;
echo "Caso 2: c = {$c->saldo}, d = {$d->saldo}<br>";
// Tu predicción: ____________


// ── CASO 3 ────────────────────────────────────────────────────────────────────
$saldos = ['ana' => 100];
$copia  = $saldos;
$copia['ana'] = 500;
echo "Caso 3: saldos = {$saldos['ana']}, copia = {$copia['ana']}<br>";
// Tu predicción: ____________


// ── CASO 4 ────────────────────────────────────────────────────────────────────
function bonificar(Cuenta $cuenta): void
{
    $cuenta->saldo += 50;
}

$e = new Cuenta('Elena', 100);
bonificar($e);
echo "Caso 4: e = {$e->saldo}<br>";
// Tu predicción: ____________


// ── CASO 5 ────────────────────────────────────────────────────────────────────
echo "Caso 5: cuentas creadas = " . Cuenta::$cuentasCreadas . "<br>";
// Tu predicción: ____________  (cuenta con cuidado cuántas veces se usó "new")


// ── CASO 6 ────────────────────────────────────────────────────────────────────
$f = new Cuenta('Felipe');
$f->consignar(100)->consignar(200)->consignar(50);
echo "Caso 6: f = {$f->saldo}<br>";
// Tu predicción: ____________


// ── CASO 7 ────────────────────────────────────────────────────────────────────
$g = new Cuenta('Gloria', 100);
$h = new Cuenta('Gloria', 100);
$i = $g;
echo "Caso 7: g == h: " . var_export($g == $h, true)
   . " | g === h: " . var_export($g === $h, true)
   . " | g === i: " . var_export($g === $i, true) . "<br>";
// Tu predicción: ____________


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> "a = 500, b = 500"
 *    $b = $a NO crea otra cuenta: $a y $b son dos nombres para el MISMO objeto.
 *    Cambiar uno es cambiar el otro.
 *
 *  CASO 2 -> "c = 100, d = 500"
 *    clone sí crea un objeto NUEVO con los mismos valores. Desde ahí son
 *    independientes. (Ojo: clone NO llama al constructor.)
 *
 *  CASO 3 -> "saldos = 100, copia = 500"
 *    Con ARRAYS es al revés que con objetos: $copia = $saldos SÍ copia.
 *    Esta es la diferencia más importante de este archivo.
 *
 *  CASO 4 -> "e = 150"
 *    La función recibe el mismo objeto (no una copia), así que el cambio se ve
 *    afuera, sin necesidad de & ni de return. Compáralo con el caso 4 del
 *    resuelto 02 de 07-funciones, donde un número NO cambiaba.
 *
 *  CASO 5 -> "cuentas creadas = 3"
 *    new se usó 3 veces (Ana, Carlos, Elena). $b = $a no crea un objeto y
 *    clone NO ejecuta el constructor, por eso el contador no sube con $d.
 *    Una propiedad static es de la CLASE: hay un solo contador para todas.
 *
 *  CASO 6 -> "f = 350"
 *    consignar() devuelve $this, el mismo objeto, y eso permite encadenar
 *    llamadas. Laravel usa esto todo el tiempo:
 *    Estudiante::where(...)->orderBy(...)->get()
 *
 *  CASO 7 -> "g == h: true | g === h: false | g === i: true"
 *    ==  compara si tienen los mismos valores (misma clase, mismos datos).
 *    === compara si son EL MISMO objeto. $g y $h son gemelos, pero distintos;
 *    $g y $i son el mismo.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, b = a comparte el objeto tanto para objetos como para listas y
 *  diccionarios. En PHP los OBJETOS se comparten igual que en Python, pero los
 *  ARRAYS se copian (caso 3). Es el error más común al pasar de Python a PHP:
 *  modificar un array dentro de una función y esperar que cambie afuera.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Si cambias "self::$cuentasCreadas++" por "$this->cuentasCreadas++"
 *     (y quitas static), ¿qué imprimiría el caso 5? ¿Por qué?
 *  2. ¿En qué situación real necesitarías clone?
 *  3. ¿Qué tendría que devolver consignar() para que NO se pudiera encadenar?
 */
