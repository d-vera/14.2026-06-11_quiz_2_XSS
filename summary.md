# Security Documentation: XSS & Brute Force Attacks

This document provides a comprehensive summary of the security concepts, code examples, and exercises contained within the directories [11.2026-05-27_Laravel_XSS](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS) and [13.2026-06-10_Brute_Force_Attacks](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/13.2026-06-10_Brute_Force_Attacks).

---

## 1. Cross-Site Scripting (XSS)
**Directory:** [11.2026-05-27_Laravel_XSS](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS)  
**PDF Presentation:** [XSS.pdf](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/XSS.pdf) by Ing. Pável Cáceres

### Concept Definition
**Cross-Site Scripting (XSS)** is a web application vulnerability that allows attackers to inject malicious scripts (usually JavaScript) into web pages viewed by other users. This bypasses the Same-Origin Policy (SOP) and can lead to session hijacking, defacement, or redirection to malicious sites.

There are two primary types covered in this module:
*   **Reflected XSS (Reflejado):** The malicious script is part of the request (e.g., via URL parameter or form input) and is immediately reflected in the response without proper sanitization.
*   **Stored XSS (Persistente):** The malicious script is permanently stored on the target server (e.g., in a database, file, or comment log). When other users request the stored data, the script executes in their browsers.

---

### Reflected XSS: Code Comparison

#### ❌ Vulnerable Version
In the vulnerable version, the name parameter is taken directly from the query string and printed back.

*   **Form:** [formulario.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss/reflected/formulario.php)
*   **Processor:** [procesar.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss/reflected/procesar.php)

```php
<?php
// Vulnerable: Outputting raw GET variable directly
$nombre = $_GET['nombre'];
echo "Hola, $nombre!";
?>
```
*   **Attack Vector Example:**
    `procesar.php?nombre=<script>alert('XSS!')</script>`

####  Secure Version
The secure version escapes output special characters using `htmlspecialchars()`.

*   **Form:** [formulario.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss-seguro/reflected/formulario.php)
*   **Processor:** [procesar.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss-seguro/reflected/procesar.php)

```php
<?php
// Secure: Converting special characters to HTML entities
$nombre = htmlspecialchars($_GET['nombre'], ENT_QUOTES, 'UTF-8');
echo "Hola, $nombre!";
?>
```

---

### Stored XSS: Code Comparison

#### ❌ Vulnerable Version
Comments are saved raw into a text file and read back/displayed without escaping.

*   **Form:** [formulario.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss/stored/formulario.php)
*   **Saver:** [guardar.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss/stored/guardar.php)
*   **Viewer:** [ver.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss/stored/ver.php)

```php
// guardar.php
<?php
$comentario = $_POST['comentario'];
file_put_contents("comentarios.txt", $comentario . "\n", FILE_APPEND);
echo "Comentario guardado. <a href='ver.php'>Ver comentarios</a>";
?>
```
```php
// ver.php
<?php
$comentarios = file("comentarios.txt");
foreach ($comentarios as $comentario) {
    echo "<p>$comentario</p>"; // Vulnerable output
}
?>
```
*   **Attack Vector Example:** Submit comment as `<script>alert('XSS Persistente!')</script>`

####  Secure Version
The secure version sanitizes the input before saving it by converting characters into HTML entities.

*   **Form:** [formulario.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss-seguro/stored/formulario.php)
*   **Saver:** [guardar.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss-seguro/stored/guardar.php)
*   **Viewer:** [ver.php](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/11.2026-05-27_Laravel_XSS/xss-seguro/stored/ver.php)

```php
// guardar.php
<?php
// Secure: Sanitizing input before file storage
$comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
file_put_contents("comentarios.txt", $comentario . "\n", FILE_APPEND);
echo "Comentario guardado. <a href='ver.php'>Ver comentarios</a>";
?>
```

---

### Core Mitigation Functions in PHP

1.  `htmlspecialchars($string, $flags, $encoding)`
    *   **Purpose:** Converts special characters to HTML entities.
    *   **Conversions:**
        *   `&` &rarr; `&amp;`
        *   `"` &rarr; `&quot;`
        *   `'` &rarr; `&#039;` (with `ENT_QUOTES`)
        *   `<` &rarr; `&lt;`
        *   `>` &rarr; `&gt;`
2.  `filter_input(type, variable, filter)`
    *   **Purpose:** Retrieves and filters external variables securely.
    *   **Filter used:** `FILTER_SANITIZE_FULL_SPECIAL_CHARS` (equivalent to calling `htmlspecialchars` with `ENT_QUOTES`).

---

## 2. Brute Force Attacks
**Directory:** [13.2026-06-10_Brute_Force_Attacks](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/13.2026-06-10_Brute_Force_Attacks)  
**PDF Presentation:** [ataquesFB.pdf](file:///Users/dennisvera/github/14.2026-06-11_quiz_2_XSS/13.2026-06-10_Brute_Force_Attacks/ataquesFB.pdf) by Ing. Pável Cáceres

### Concept Definition
A **Brute Force Attack** is a technique that consists of systematically trying all possible password combinations to guess system credentials.

### Types of Brute Force Attacks
*   **Simple Brute Force:** Generates combinations sequentially (e.g., `abc`, `abc1`, `abc2`...).
*   **Dictionary Attack:** Tests a pre-defined list of common/known passwords instead of random characters.
*   **Hybrid Attack:** Combines dictionary words with random character combinations (e.g., adding numbers/symbols to dictionary words).
*   **Distributed Attack:** Spreads the effort across multiple machines/IP addresses to bypass rate limiting.

### Lab Exercise Objectives
The assignments in this module require modifying the provided scripts to shift hardcoded parameters to dynamic configurations managed from database tables:

1.  **`00ataquefb` (Password Dictionary):**
    *   *Change:* Modify the script to read the list of passwords (dictionary) from a database table instead of a flat text file.
2.  **`01limitarintentos` (Attempts Limit):**
    *   *Change:* Fetch the maximum limit of failed login attempts dynamically from a configuration table in the database.
3.  **`02bloqueoip` (IP Blocking):**
    *   *Change:* Store and query blocked IP addresses dynamically inside a database table rather than using static configurations.
