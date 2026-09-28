# Quickstart — v1: Investigación (PHP + MariaDB)

docker compose up -d --build

## Antes de empezar: por qué aquí NO se usa curl

→ variable $API
→ función pedir(...)
→ nota para Git Bash/Linux/macOS

## Los criterios de la v1

1. Diagnóstico de la API
2. Comprobar semillas iniciales
3. Crear y listar
4. Ciclo GET / POST / PUT / PATCH / DELETE
4b. Diferencia entre PUT y PATCH
5. Comprobar borrado lógico
6. Comprobar validaciones y conflictos
7. Prueba de capas sin MariaDB

## Y la pantalla

### A mano
→ comprobar los 6 recursos
→ crear
→ editar
→ PUT vs PATCH
→ retirar
→ probar lista vacía
→ apagar API y comprobar separación

### Con el guion
→ pruebas_humo/humo_front.py

## Si algo sale mal

→ tabla problema / causa / solución