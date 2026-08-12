<?php
return [
    'sp' => [
        'entityId' => 'moneyguide_giftingnetwork',
        'assertionConsumerService' => [
            'url' => 'https://uat-ffp.giftingnetwork.org/m/saml/login',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_POST,
        ],
        'singleLogoutService' => [
            'url' => 'https://uat-ffp.giftingnetwork.org/m/saml/logout',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_REDIRECT,
        ],
        'NameIDFormat' => \OneLogin\Saml2\Constants::NAMEID_UNSPECIFIED,
        'x509cert' => 'MIIDFjCCAf4CCQDvteGpc/xY0zANBgkqhkiG9w0BAQUFADBNMQswCQYDVQQGEwJJ
        TjEOMAwGA1UEAxMFUmFqYW4xLjAsBgkqhkiG9w0BCQEWH3JhamFua3Rpd2FyaUBn
        aWZ0aW5nbmV0d29yay5jb20wHhcNMjMwODMxMDgyODA4WhcNMjQwODMwMDgyODA4
        WjBNMQswCQYDVQQGEwJJTjEOMAwGA1UEAxMFUmFqYW4xLjAsBgkqhkiG9w0BCQEW
        H3JhamFua3Rpd2FyaUBnaWZ0aW5nbmV0d29yay5jb20wggEiMA0GCSqGSIb3DQEB
        AQUAA4IBDwAwggEKAoIBAQDdiYM0BUz32yce2DIdraEYON1YyakXaIF+qAt+iCOy
        u0LJuWY6QqE0xwtvEkzSzRDJAUOxCKLHEuu/D2rck1rTT+Fel7KxFyRaSHRpWeyz
        nq2CmzOAKS+oXgCXwyeCe+AIXDhxFcmB0vcO7i65/7XHeMUIR35Nl2KXm58UKC+g
        PZlnI8CUAfJubz9YiaECnhwqawHzi3BkvcHA8MGtY0NUdzxKx3H3Z+HTyXN+ak8p
        17nprPocGD6YkEyZWFSxO4gZJhrQTLcF9YR/WOBIb+CMzkR5JdXlGFXTB/PzKuBg
        8e0sD87P8OA3tfDzTjd9+lDocrgXm9p9MIQD7vAjWfMPAgMBAAEwDQYJKoZIhvcN
        AQEFBQADggEBABJsKUTDKgoWcNWPDtzp22Rvxk6DI/dV0+R+7BaJcw6dOlmL6GXp
        RNx8zNXPWfZLwhL9Qy4BSegVFFJJrZpQsI+dwJ6OyU7pZwTxU8GDSHewj5GYcPsS
        wLLpWyrLQiOeYuz6uLVTl0HdFjoJO7IlacS8RFpPTg3O4KQRKru3VoRY6JbdxL8p
        lfCNAtrnsQ6jX20zHMOq7tOHWURXgm1zD204vpl9cB9mMQubP6gWtHDv/YsZkAdz
        Qj4eRTPkJUdUpmU9anYwihQGBYXWVe/9GgCBGNzQRt5zNDIJ4HlQYqIXLRnEiPAt
        I1EwsgtHX9PaxFhNZmI4aXETEcob6GD4hqA=',  // Add your SP x509 certificate here

        'privateKey' => 'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDdiYM0BUz32yce
        2DIdraEYON1YyakXaIF+qAt+iCOyu0LJuWY6QqE0xwtvEkzSzRDJAUOxCKLHEuu/
        D2rck1rTT+Fel7KxFyRaSHRpWeyznq2CmzOAKS+oXgCXwyeCe+AIXDhxFcmB0vcO
        7i65/7XHeMUIR35Nl2KXm58UKC+gPZlnI8CUAfJubz9YiaECnhwqawHzi3BkvcHA
        8MGtY0NUdzxKx3H3Z+HTyXN+ak8p17nprPocGD6YkEyZWFSxO4gZJhrQTLcF9YR/
        WOBIb+CMzkR5JdXlGFXTB/PzKuBg8e0sD87P8OA3tfDzTjd9+lDocrgXm9p9MIQD
        7vAjWfMPAgMBAAECggEBAM/9/Vrn5x2FejMxU8wdafPt35Musjyx93JMn44Kj0mN
        GuV91Ya5a9S7U/bSPaJkuW+eaklaDEnPb9qPxQqWpl1iz/sZfcFaMt04zWLj9KGn
        Rswqnq2i/YWby/6wUPXnC62Nt8gkZm4m/aHc6a3jcG1467QcO27pxL0GdNJd8GkF
        7QU2gVDMEDjhoDaRargg9rnHypGRD6Knk9qhgvWM/AH7yeXU+1OBpx01ojKz+s61
        BA2YIW5X2Xsiyrjln6zCdn8CeJEHJ2PiUhbXvRCF5pHqexYsg0Jy83cJrx7RoVQo
        y9spN1NXVcOpwC37dPQqkyRB9hxzscMlahkNOAwji4ECgYEA9j26L7rRJThIk0pi
        x3Rr4ULi8BrzbakFYCiVCYSYZ3jsm/8wHVXYUdwz/XlPtk3uC6n4/HYOpT5V4QJZ
        Urf1UU3YOXNI+Jd221XQoTNTEEItAJVpeHadkgHwkMveuaVNlwWMYwj8F//x9LiK
        mVRppyCOVulDbLewN6GiI3Nisa8CgYEA5lEl1nuOLWAD72F/dNJSnunEi9yW6EPQ
        2gJyerpt57gxstYcDEsZm4nKorYebUdBnYd0nvw7HE54I4evDCYdyOHiP4LG8qaa
        /pDMXzv6ApOzrPugogkPgXbJF8/QbHCo2tbvDZAXlbiSET/3Y1mfJRahaNWzelU6
        NmfpSm5MDKECgYEA3BNNLU1tkTNdge6wnAzMQjaydt/PgsnWWRvMIAaW45uq9L09
        dmp7/KWu/bMHcCr0Pw55zsGA02UEAidER+7L3Q+S2b3UkQFmJB/tZ7VkXpqWd/gR
        nK1+pLkBFZhwBkHcN8aosvabwKNqz0qD7QgJqWoqbTjrnuYJI59dNwvwQFcCgYAl
        zMOtA/I9uDhtHU9R2QL+WdeMLNQHormwTh6Dzf0jvrNJaqKH0fuN73t2YPV1cfBu
        7kBRp4v8BVTqX+Z20/qncYs0aT5FrNpbVWB1cN6DdcJTbajbbylAUkKGyfCnG4Zb
        BFOPokp8pCI0+o6bm6Xm7GPpVMl0FnDLZRqAwMFygQKBgQCBVrZ5RHnMEXjPRoMa
        XLEQ2lCIrFQTfoiD439TsRCV4fZjvkDqO/18hLoxaag7YbZcxVJaSMZa3Z6J6b4k
        D1AOiDCiHIFqxHKs2Iy3qm6Pxi5mpXES0HHtduR1MV58P2YxP2/zHuUC6p5Ul5o8
        98aRquAeq7IO5pTiMxQ6r/w/0g==', // Add your SP private key here
        // Other SAML configuration settings
    ],
    'idp' => [
        'entityId' => 'http://www.okta.com/exkf0q368dGi2k8Dm5d7',
        'singleSignOnService' => [
            'url' => 'https://dev-46082845.okta.com/app/dev-46082845_moneyguideffp_1/exkf0q368dGi2k8Dm5d7/sso/saml',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_REDIRECT,
        ],
        'singleLogoutService' => [
            'url' => 'https://dev-46082845.okta.com',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_REDIRECT,
        ],
        'x509cert' => 'MIIDqDCCApCgAwIBAgIGAY2IXRU7MA0GCSqGSIb3DQEBCwUAMIGUMQswCQYDVQQGEwJVUzETMBEG
        A1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsGA1UECgwET2t0YTEU
        MBIGA1UECwwLU1NPUHJvdmlkZXIxFTATBgNVBAMMDGRldi00NjA4Mjg0NTEcMBoGCSqGSIb3DQEJ
        ARYNaW5mb0Bva3RhLmNvbTAeFw0yNDAyMDgxMDU1NThaFw0zNDAyMDgxMDU2NTdaMIGUMQswCQYD
        VQQGEwJVUzETMBEGA1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsG
        A1UECgwET2t0YTEUMBIGA1UECwwLU1NPUHJvdmlkZXIxFTATBgNVBAMMDGRldi00NjA4Mjg0NTEc
        MBoGCSqGSIb3DQEJARYNaW5mb0Bva3RhLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoC
        ggEBAMSwAU2B2YdNifK4Tas3y/vCXwa5mzKhWeXckDXb9lZ+a748ltwO2uBcBCKQNokzaDvPMhBs
        8B2o8kW0n72ovwZ2WExu96IH/DYnk6ev0razvgNuvTQ3vk2A5UhRHCsb+qqNddNCw6jYWzskBdhP
        hPvd7/xKNb+BAqgkOCKDWd6uthg8k/i8cB3gPFzeMy7FkmEwYAxMRbUPKn7X6j2tAlL9B3RLVR4b
        7bqEmJ3N8FtgdtZvKkxeoqi58n2AyafozrmDLdyUV6KmCm9n6plbYdvXgCYk69obVK9rSi1aV0q/
        KLAjqnKmlVW5Q0IAkliwIWAkmFkAS5BIHk6hP7wSp0ECAwEAATANBgkqhkiG9w0BAQsFAAOCAQEA
        qIEPOIvSTkXG1pTD2wYE3/fUouQrN7YaHsPbCvBwixBVtRwguRXWUQnDzB7KcET7TF1nX0hZxRbw
        THP6LVlVkZPxJmwHtSS2HbdMtETZZggUwE6jmPWyCd+faL0AGTbUOmOzvu6cLYPvmR4yYiJwD8ke
        NIbrbPSxLDSpgeoQAGzjE0FIg+DeBpBB3eCcGKZb5tI6vDUCCw/Fe8I7pK7yQHAJ54ZYp/GOUnE3
        IfecCeIZ40+PcYQSaTpEiXHxMWF4C/WBq8uFittv/iGexD/vQmOv5REpwia1BsKUn2IBZZ7fe1/O
        lSqZEmbN+xHDF+QKc6lOCt40wLwM3CWqHKs6Bw==',  // Add your IdP x509 certificate here
    ],
    // Other SAML configurations
];
