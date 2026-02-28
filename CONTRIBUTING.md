# Guía de Integración y Trabajo Colaborativo 🚀

¡Bienvenido al equipo de desarrollo! Este proyecto está construido bajo los principios de **Clean Architecture** (Arquitectura Limpia). Nuestro objetivo principal es mantener la lógica de negocio 100% aislada de los frameworks (React/Android/Laravel), la base de datos y la red.

Para garantizar la calidad y escalabilidad del código, todo el equipo debe adherirse a este manual.

## 1. Reglas Arquitectónicas Inquebrantables 🛡️

Antes de escribir una sola línea de código, recuerda La Regla de Dependencia: **El código fuente solo puede apuntar hacia adentro**.

1. **El Dominio es Sagrado:** La capa de `domain` (Entidades e Interfaces/Repositorios) NO puede importar NADA de `infrastructure`, `presentation` o dependencias de terceros (como Axios, Retrofit o Eloquent).
2. **Validación Temprana:** Toda entidad de negocio debe autovalidarse en su constructor (ej. un `Exercise` no puede aceptar series negativas).
3. **Casos de Uso Aislados:** La capa de `application/useCases` solo debe coordinar el flujo. Habla con la base de datos a través de interfaces, NUNCA mediante implementaciones concretas.
4. **Mappers:** Todo dato que venga de una API externa o de una base de datos debe pasar por un `Mapper` para convertirse en una Entidad de Dominio pura antes de entrar a la UI o a los Casos de Uso.

## 2. Flujo de Trabajo (Git Workflow) 🌿

Utilizamos un modelo basado en **Feature Branches** y Pull Requests.

1. **Nunca trabajes en `main` o `develop` directamente.**
2. Actualiza tu rama local: `git pull origin develop`.
3. Crea una nueva rama descriptiva para tu tarea:
    - Características nuevas: `feature/nombre-de-tu-feature` (ej. `feature/modulo-rutinas`)
    - Corrección de errores: `fix/nombre-del-bug` (ej. `fix/error-series-negativas`)
4. Realiza commits atómicos (pequeños y enfocados en una sola cosa).

## 3. Pruebas Automatizadas (El Requisito Obligatorio) 🧪

Dado que nuestras reglas de negocio están aisladas, hacer pruebas es fácil y rápido.
**Ningún Pull Request será aceptado si no incluye sus respectivas pruebas unitarias.**

* **Frontend (React):** Debes probar tus Entidades y Casos de uso con Vitest. Ejecuta `npm run test` localmente.
* **Android (Kotlin):** Debes probar tus Casos de Uso "mockeando" el repositorio. Ejecuta `./gradlew testDebugUnitTest`.
* **Backend (Laravel):** Debes asegurar las transacciones y validaciones de dominio. Ejecuta `php artisan test`.

## 4. El Proceso de Pull Request (PR) y GitHub Actions 🚦

Cuando tu tarea esté lista, abre un Pull Request hacia la rama `develop`.

Al abrir el PR, **GitHub Actions se disparará automáticamente**. Nuestro servidor de CI (Integración Continua) realizará lo siguiente:
1. Compilará tu código.
2. Ejecutará todas las pruebas automatizadas.
3. Revisará reglas de formato y tipado.

### El Botón de Merge está Bloqueado si:
* ❌ Las pruebas de GitHub Actions fallan (Luz Roja). Deberás revisar los logs, corregir tu código en tu rama y hacer un nuevo *push*.
* ❌ Falta la aprobación de un Code Reviewer.

### Rol del Code Reviewer (Revisor) 👀
El compañero que revise tu código no solo buscará errores de sintaxis, sino que evaluará la arquitectura:
* ¿Hay lógica de negocio filtrada en el componente de UI o en el Controlador?
* ¿Se inyectaron las dependencias correctamente?
* ¿Se crearon las pruebas necesarias para el nuevo Caso de Uso?

Una vez que GitHub Actions esté en Verde (✅) y tengas la aprobación, puedes hacer *Merge* de tu código.
