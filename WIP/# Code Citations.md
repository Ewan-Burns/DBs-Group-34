# Code Citations

## License: MIT
https://github.com/katsiaryina/gringotts/tree/1c0359d8332c588df857b57572999c8e3c45fb23/config/signup.php

```
email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result
```


## License: unknown
https://github.com/norelk/LandingpagePHP/tree/12a78c956a0ddaad54ff7a801af5d6d7fc483f8a/LandingPagePhp/include/functions.php

```
stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc()
```


## License: unknown
https://github.com/vladciocoiu/php-project/tree/d9166b726d5dac191f452cb87f5ad3bd31f71714/project/controllers/borrowings_controller.php

```
= $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1
```


## License: unknown
https://github.com/minhle3107/php1/tree/9a25e1e1a4710d78b08be580f27e899cfa1dac0f/frontend/client/cart.php

```
WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($
```


## License: unknown
https://github.com/KwameGilbert/Intern-Record-System/tree/b3c6d346ad531fa7bdd844818b2de009158fef7e/intern/php/intern_login.php

```
);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($
```

