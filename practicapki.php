server {
    #listen 8080;
    #server_name practicapki.com;


    listen 443 ssl;
    server_name practicapki.com;

    # Certificados del servidor
    ssl_certificate /home/kali/Documentos/pki/servidor/servidor.crt;
    ssl_certificate_key /home/kali/Documentos/pki/servidor/servidor.key;

    # Cadena de certificados para confianza
    ssl_trusted_certificate /home/kali/Documentos/pki/ca/ca.crt;

    # Habilitar autenticación mutua (cliente debe presentar un certificado válido)
    ssl_client_certificate /home/kali/Documentos/pki/ca/ca.crt;
    ssl_verify_client on;

    # Opcionales: ajustes de seguridad SSL
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;


    location / {
        root /var/www/practicapki/html;
        index index.html index.php;
        #try_files $uri /cert_info.php;
   
	}
    # Procesar solicitudes PHP
    location ~ \.php$ {
        root /var/www/practicapki/html;
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;  # Ajustar si es necesario
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SSL_CLIENT_CERT $ssl_client_cert;
        include fastcgi_params;
    }
    location /admin {
    root /var/www/practicapki/admin;
    index index.php;
    if ($ssl_client_s_dn !~* "CN=ruben") {
    return 403;
    }
}

}
