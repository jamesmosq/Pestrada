# Soluciones de los ejercicios

Cada módulo del 16 al 21 tiene un `EJERCICIOS.md` con 12 ejercicios en tres niveles:
básico (1–4), intermedio (5–9) y reto (10–12). Aquí están sus soluciones.

## Cómo usar estas soluciones

Las soluciones están a tu alcance para que **practiques de manera consciente**, no para copiarlas.
Copiar el código te deja con un ejercicio "hecho" y sin haber aprendido nada. Un buen método:

1. **Intenta primero.** Dedica al menos 20 minutos a cada ejercicio sin abrir la solución.
2. **Si te bloqueas,** relee el enunciado, la **Pista** y los ejemplos del módulo (`1.php`, `2.php`, …).
3. **Cuando lo termines,** compara con la solución. Pregúntate: ¿qué hice distinto? ¿Cuál es más claro? ¿El mío
   cubre los mismos casos (vacío, inválido, con tildes, repetido)?
4. **Si miraste la solución,** ciérrala y escribe el ejercicio otra vez desde cero al día siguiente.
5. **Lee siempre la parte "Para entender"**: explica el error típico o el concepto detrás de la solución.

Las soluciones son **una** forma de resolverlo. Si la tuya funciona, cumple la salida esperada y se entiende,
también es correcta.

| Módulo | Enunciados | Soluciones |
|---|---|---|
| 16 Strings | [`16-strings/EJERCICIOS.md`](../16-strings/EJERCICIOS.md) | [`16-strings.md`](16-strings.md) |
| 17 Fechas | [`17-fechas/EJERCICIOS.md`](../17-fechas/EJERCICIOS.md) | [`17-fechas.md`](17-fechas.md) |
| 18 Errores | [`18-errores/EJERCICIOS.md`](../18-errores/EJERCICIOS.md) | [`18-errores.md`](18-errores.md) |
| 19 Archivos | [`19-archivos/EJERCICIOS.md`](../19-archivos/EJERCICIOS.md) | [`19-archivos.md`](19-archivos.md) |
| 20 Formularios | [`20-formularios-validacion/EJERCICIOS.md`](../20-formularios-validacion/EJERCICIOS.md) | [`20-formularios-validacion.md`](20-formularios-validacion.md) |
| 21 SweetAlert2 + MVC | [`21-sweetAlert2/EJERCICIOS.md`](../21-sweetAlert2/EJERCICIOS.md) | [`21-sweetAlert2.md`](21-sweetAlert2.md) y el proyecto completo [`21-proyecto-final/`](21-proyecto-final/) |

## Sobre el proyecto final del módulo 21

`21-proyecto-final/` es el proyecto con los 12 ejercicios del módulo 21 terminados. Úsalo para comparar
**después** de construir el tuyo en `21-mi-proyecto`. Para abrirlo, primero ejecuta
`21-proyecto-final/migracion.sql` en phpMyAdmin.

## Cómo se verificaron

- **16 a 19:** cada solución se ejecutó con PHP 8.3 y su salida se comparó con la "salida esperada" del enunciado.
- **19 (libro de visitas y subida de archivos) y 20:** se probaron como páginas web con peticiones reales
  (incluyendo intentos de XSS, archivos falsos y un ataque CSRF).
- **21:** el proyecto final se probó completo contra una copia de la base `sena_mvc`.

Las notas «Puente a Laravel» de los enunciados muestran cómo se hace lo mismo en Laravel.
