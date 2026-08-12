<?php
return [
    'sp' => [
        'entityId' => 'https://uat-gna.giftingnetwork.org',
        'assertionConsumerService' => [
            'url' => 'http://agency-advisor/m/envestnet/okta/cb/login',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_POST,
        ],
        'singleLogoutService' => [
            'url' => 'http://agency-advisor/m/envestnet/okta/cb/logout',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_REDIRECT,
        ],
        'NameIDFormat' => \OneLogin\Saml2\Constants::NAMEID_UNSPECIFIED,
        'x509cert' => 'MIID4jCCAsoCCQCgR0Y/jtYvITANBgkqhkiG9w0BAQUFADCBsjELMAkGA1UEBhMC
VVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxEzARBgNVBAcTCk1vcnJpc3Rvd24xFzAV
BgNVBAoTDkdpZnRpbmdOZXR3b3JrMQswCQYDVQQLEwJJVDEjMCEGA1UEAxMadWF0
LWNnZi5naWZ0aW5nbmV0d29yay5vcmcxLjAsBgkqhkiG9w0BCQEWH2Fsa2VzaGtz
aW5naEBnaWZ0aW5nbmV0d29yay5jb20wHhcNMjUwNjE3MDg0MzI3WhcNMjYwNjE3
MDg0MzI3WjCBsjELMAkGA1UEBhMCVVMxEzARBgNVBAgTCk5ldyBKZXJzZXkxEzAR
BgNVBAcTCk1vcnJpc3Rvd24xFzAVBgNVBAoTDkdpZnRpbmdOZXR3b3JrMQswCQYD
VQQLEwJJVDEjMCEGA1UEAxMadWF0LWNnZi5naWZ0aW5nbmV0d29yay5vcmcxLjAs
BgkqhkiG9w0BCQEWH2Fsa2VzaGtzaW5naEBnaWZ0aW5nbmV0d29yay5jb20wggEi
MA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC3V7Fi/cgZimBpZ3RqffFlvGal
yXxCAtnFNGDnWm4lfao1SDAH+qGcSBgcizJZhKinySyRDmd/XfBaBxWer0UbXz5O
meeT8Uh0T+8lYGrXgu9S8MKu9svgNe9TV7c5FJhXaVpRmdbYBshiytvUzGzcX775
26XrBxQHM8nKWTHRFf8WGJks1lIP5BW7zhBVmGddFqKjDAgM09qGJVkvj9RRuSoZ
MR2RoJlTgYLa0qdGcsVclLon0TN37R2q7IWu3mVb+gELL8l2gxeIprsGN8PuseJ/
pmDp7g8C/wYKrhrUGTndN5Ex3pvj1fUyUJjB1b2JkMK2DXkcg5D5kK53D49ZAgMB
AAEwDQYJKoZIhvcNAQEFBQADggEBAATfW9uE6DNsK2IfLB/dIKMT/C9nPm8fQVzw
6WqqFj7MAkwbE3qza7zpL/qlpOy4glvFtVOIqN231HbLOK+i/DmFWHS4pqOglR17
dy2eBRLOkuosIrQsIhC2qNRjkk5N3TT0RaWBJrejfs9KQeHuzj+BJ/92T/d+qYKT
RazlcM5R60nrZXfIOUdjYkMQIuMa16YOs9+PjMfkPfhti02GCze6AGTU2Nt/M3A7
GHO+UDspfZN+Y0JNdX3dlQ2G/i7jRZXnQ9xqN74vBfaO0yFT47R2u9L7Hergm6Aw
7cWjpaRHeP6Wvv0ZO1o6jHtWhvWMzSDJGC3geIayDW1wiwLukzM=',  // Add your SP x509 certificate here

        'privateKey' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC3V7Fi/cgZimBp
Z3RqffFlvGalyXxCAtnFNGDnWm4lfao1SDAH+qGcSBgcizJZhKinySyRDmd/XfBa
BxWer0UbXz5OmeeT8Uh0T+8lYGrXgu9S8MKu9svgNe9TV7c5FJhXaVpRmdbYBshi
ytvUzGzcX77526XrBxQHM8nKWTHRFf8WGJks1lIP5BW7zhBVmGddFqKjDAgM09qG
JVkvj9RRuSoZMR2RoJlTgYLa0qdGcsVclLon0TN37R2q7IWu3mVb+gELL8l2gxeI
prsGN8PuseJ/pmDp7g8C/wYKrhrUGTndN5Ex3pvj1fUyUJjB1b2JkMK2DXkcg5D5
kK53D49ZAgMBAAECggEAJW/MktALOdvdj+hGBzCsR//OXe/kZX47hV8KonL+pr0g
Mj0JQbfVpBm8gqeRomHKJmiXpw7v5zwBRgDIU1yqmco66MXYWYcdKlLgHBux9UuR
Hrh2qE9QlfmtYrE4gnlIGiEzBhKJohbQeAvAE5WibOVIE7DcEj+hwU6714ua9nxU
3P/FkfUUva8Dxoq1sHvXcYm+PA6OFFLzYkzRpOvOATLE/s1YpKe4c8QfZHyf7DWt
e5TeeE9dSPOy+Mc0GKOs2r7t9AGL6QBHVGJ38iu8L20ljjlBe+wma495Rm7XNQDq
myhITSRDF669rluje+/63KCrE/pRflDHErMZiyNkYQKBgQDa4n0S6aNjiyZfCQ5Z
WxklWnwneeUjil7iUZayJnR3Aq47UMkklBbADsB+2SQEyNjGcu6xdVyfxC/q262E
0kSdqzE3GRLFUOns30be+Ub47mvmnBYA6Wz0eUVwXy8CakOV8/rUKyr2ceEi9Or8
171axhfDnp1oLC7Efrtfhbb5NQKBgQDWblz/BHCEfAW64ImIn1TnsHlaG43s1WK5
lmKT30K3eB6YSM0kwpxOqUC+qsrmuyuLRA0Rb6fuGvJX84N5AJHerU6InhCfFz8/
nZgYkPHlTU3ikpbsUYIrHDnRPjkCYOEWbEMPy1liuFvKwg79mels0H16gdAl5LHl
2z76kvpmFQKBgQDYoIFCn4K6OPQ/6DmQS+2mH1hyxVv3AbIIcNEg/ZgUVM3VHI4F
qp6dc7wljofsSE7qkMMVVF7YLBV0bffcVTHdjZlGu4TtTbhGW2/Lq5AHPAgEEwRf
4KWZixAjN8DRthOvq+jZ5OM/RtOXgiezF11rIlRQoYGNoPSkOfz9e9vr5QKBgBEx
okVUjs0gwLjeXkjVNdKqJODjyrqN1a+57kebJFdfy1w8oi5raGsqSSXAvipIIdK1
7zJRuK5v/LSuswEV2Zx4Ww67VevbyleIhHSb0rmnPDKJvI5QbtaUGdK8YYfeSs4o
eiwfCxjOXZXhf6A4Ii52fUlAdXdEYjtjHCateGSNAoGAWKoSgP3xIx3qN3Y95SDz
MZH09tVlTwYA2qn2+5HGH8cfcrnOcW0/ivlRVWRfFof4GV/dru+rL2ZNpKfVHqJY
Pk+QnzFRjUljv5fOB8uvroRDitIF8pjEbaZbFW3IqRao3STwelat3A/e+/k1KmdL
hxQ/SxvUa8voPP06SKk+/Ko=', // Add your SP private key here
        // Other SAML configuration settings
    ],
    'idp' => [
        'entityId' => 'http://www.okta.com/exkxiwdlxl7o24Lt8697',
        'singleSignOnService' => [
            'url' => 'https://integrator-9164560.okta.com/app/integrator-9164560_envestnetgn_1/exkxiwdlxl7o24Lt8697/sso/saml',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_REDIRECT,
        ],
        'singleLogoutService' => [
            'url' => 'https://integrator-9164560.okta.com',
            'binding' => \OneLogin\Saml2\Constants::BINDING_HTTP_REDIRECT,
        ],
        'x509cert' => 'MIIDtDCCApygAwIBAgIGAZqWuq99MA0GCSqGSIb3DQEBCwUAMIGaMQswCQYDVQQGEwJVUzETMBEG
A1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsGA1UECgwET2t0YTEU
MBIGA1UECwwLU1NPUHJvdmlkZXIxGzAZBgNVBAMMEmludGVncmF0b3ItOTE2NDU2MDEcMBoGCSqG
SIb3DQEJARYNaW5mb0Bva3RhLmNvbTAeFw0yNTExMTgxMTI5MDhaFw0zNTExMTgxMTMwMDhaMIGa
MQswCQYDVQQGEwJVUzETMBEGA1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNj
bzENMAsGA1UECgwET2t0YTEUMBIGA1UECwwLU1NPUHJvdmlkZXIxGzAZBgNVBAMMEmludGVncmF0
b3ItOTE2NDU2MDEcMBoGCSqGSIb3DQEJARYNaW5mb0Bva3RhLmNvbTCCASIwDQYJKoZIhvcNAQEB
BQADggEPADCCAQoCggEBAKQ/zR1DNh1kp3lgCTI1kb3kIy4VYDV7sSLTPwQ80tkIXBbUIYZM1rLd
rz1cc4WKrXQYk7ntllnSIODFhGp8ZUJCfRhSfI3qTE992Wy3YARPBrjtYEUMNn0ZvnLHbBB8jqWi
UpjhU7vHkO3WXgN0+aw+XQx6hlbxT7ePuLxjTfxg1+g6G2+WSB127ZaLTSwvEQ733l92G9y8F4B8
GWeB9Z2ZQxFXBb3FtJ4DqHcaoS5dlZOjadEctyGJVtKzTMJpfI/E2xwKM/9CujAi5hTeYjOkeWYQ
aYuXLRf7qpRINAj6LwbOzLpu4IsMxSplkqF4nhlv2EppZO8kxxa68uIQmTsCAwEAATANBgkqhkiG
9w0BAQsFAAOCAQEAd+dnVpN47zMYKi0t3fNGxm5aVftL3yhp465NiFDgBSI45m6HqHPcMDnw6qIm
D8TypollI4eFuTfRBGc3VidOHHp/c9gxFY8mC4bAvn0rkTfA8fALFaeXVvCUduGbxZUMQsGW1706
yIRjmqpAOZG9CGdODQwUaU+Np0zZOCux/NOp54ivHfIoF8049NcbdogtF8mrbAp58qvzAerAA1eE
RZ1GgvSLlp+bSGpUZVdABSkI/4TWiW0vllDfHKIFag12Mj4oXDRIoPrxmRIsyrTAzy1nHzmkKcdt
utXo6LD9INdQiuRzo1g2d7kA3Aa4eSbO8kH6MWpIpwmsO75TajZ1vQ==',  // Add your IdP x509 certificate here
    ],
    // Other SAML configurations
];
