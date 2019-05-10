<?php namespace ewma\dev\controllers;

class Openssl extends \Controller
{
    public function getAvailableCiphers()
    {
        $ciphers = openssl_get_cipher_methods();

        $ciphers = array_filter($ciphers, function ($n) {
            return stripos($n, "ecb") === false;
        });

        $ciphers = array_filter($ciphers, function ($c) {
            return stripos($c, "des") === false;
        });

        $ciphers = array_filter($ciphers, function ($c) {
            return stripos($c, "rc2") === false;
        });

        $ciphers = array_filter($ciphers, function ($c) {
            return stripos($c, "rc4") === false;
        });

        $ciphers = array_filter($ciphers, function ($c) {
            return stripos($c, "md5") === false;
        });

        return $ciphers;
    }

    public function updateConfig()
    {
        $ciphers = $this->getAvailableCiphers();

        $cipher = $this->data('cipher');

        if (in($cipher, $ciphers)) {
            $key = openssl_random_pseudo_bytes(16);
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);

            mdir(abs_path('config/.openssl'));
            write(abs_path('config/.openssl/key'), $key);
            write(abs_path('config/.openssl/iv'), $iv);

            $mainConfig = aread(abs_path('config/-/main.php'));

            ra($mainConfig, [
                'openssl/cipher' => $cipher,
                'openssl/ivlen'  => $ivlen
            ]);

            awrite(abs_path('config/-/main.php'), $mainConfig);

            return 'updated';
        } else {
            return $cipher . ' not in available ciphers';
        }
    }
}
