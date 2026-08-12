<?php
if(!isset($fullWidthFooter)) $fullWidthFooter = false;
$footerContainer = $fullWidthFooter ? 'container' : 'container-fluid';

$clientBaseUrl = \App\Models\ClientInfo::getBaseUrl();
?>
@if($fullWidthFooter)
    <style>
        .main-footer { margin: 0!important; }
    </style>
@endif
<style>
    .main-footer {
        padding-top: 1rem;
    }
    .footer-logo-svg {
        width: 100%;
        padding: 1rem 0 1rem 0.5rem;
        height: auto;
        max-width: 300px;
    }
    p.footer-address {
        padding-left: 1rem;
        color: #fff;
        font-size: 1.1rem;
        margin-top: 1rem;
    }
    .footer-list {
        margin: 1rem 0 .5rem 0;
        padding-left: 1rem;
    }
    .footer-list > a {
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .footer-list > ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .footer-list-link {

    }
    .footer-sublist {

    }

    .footer-sublist li {
        /*font-size: .9375rem;*/
        /*font-weight: 300;*/
        line-height: 1.3;
        margin-top: 0.5rem;
    }
    .footer-sublist li a {
        /*font-size: .9375rem;*/
        font-size: 1rem;
        font-weight: 300;
    }


    .gn-footer ul.social-icons {
        list-style-type: none;
        padding: 0 0 0 1rem;
        margin: 0;
    }

    .gn-footer ul.social-icons {
        line-height: 1;
        display: flex;
        align-items: flex-end;
    }
    .gn-footer ul.social-icons li {
        display: inline;
    }
    .gn-footer ul.social-icons li a {
        font-size: 1.75rem;
        margin-right: 1rem;
        color: #fff;
    }
    .gn-footer a:active,
    .gn-footer a:focus,
    .gn-footer a:hover
    {
        color: #c4c4c4;
    }

    a.footer-link {
        color: #fff;
        font-size: 1.125rem;
        font-weight: 300;
        padding-left: 1rem;
    }


    /* light*/
    .gn-footer {
        color: #212121;
        background: #eaecef!important;
        border-top: 1px solid #eee;
        --logo-text-color: #fff;
    }
    .footer-logo-svg {
        --logo-text-color: #000;
    }
    .gn-footer a {
        font-size: 1.1rem;
        color: #000;
    }
    a.footer-link {
        color: #212121;
    }
    p.footer-address {
        color: #212121;
    }
    .gn-footer ul.social-icons li a {
        color: #005c84;
    }
    .gn-powered-by a {
        background: #fff;
        font-size: 13px!important;
    }
</style>

<div class="gn-footer main-footer">

    <footer class="{{$footerContainer}} pb-3">

        <div class="row">

            <div class="col-lg-3">

                <div class="footer-logo">
                    <a target="_blank" href="https://www.cct.org/">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 193 59" preserveAspectRatio="xMidYMid meet" focusable="false" aria-hidden="true" width="327" height="99.963730569948" class="footer-logo-svg">
                            <g fill="#00c6d7">
                                <path fill="var(--logo-icon-color)" d="M27.72 45.68h4.45c.47 0 .84-.37.84-1.01V24.4h11.7c.55 0 .83-.37.83-1.02v-4.44c0-.55-.37-.83-.93-.83h-29.7c-.47 0-.84.28-.84.74v4.63c0 .55.28.92.84.92h11.7v20.27c.18.74.46 1.01 1.1 1.01Z"></path>
                                <path class="circle" fill="var(--logo-icon-color)" d="M25.49.25c.18 0 .28.1.28.37V4.5c0 .46-.28.55-.56.64A24.7 24.7 0 0 0 5.25 29.31v.09a24.52 24.52 0 0 0 19.96 24.06c.28 0 .56.09.56.65v3.88c0 .19-.1.28-.1.28-.09.1-.27.1-.37.1A29.24 29.24 0 0 1 .43 29.3 29.38 29.38 0 0 1 25.3.25Zm8.91 0c.1 0 .1 0 .19.1A29.24 29.24 0 0 1 59.46 29.4 29.08 29.08 0 0 1 34.6 58.36h-.19c-.28 0-.37-.09-.37-.28V54.2c0-.46.28-.56.56-.65A24.7 24.7 0 0 0 54.54 29.4v-.19A24.6 24.6 0 0 0 34.6 5.06c-.28 0-.56-.1-.56-.65V.53c0-.19.1-.28.37-.28Z"></path>
                            </g>
                            <g fill="#696a6d">
                                <path fill="var(--logo-text-color)" d="M74.69 53.37c1.3 0 1.67.83 1.67 2.03v.28c0 .83-.19 1.48-.75 1.85l.47.74-.37.18-.47-.83c-.18.1-.37.1-.55.1-1.21 0-1.67-.84-1.67-2.04v-.28c0-1.2.37-2.03 1.67-2.03Zm51.7 0v2.86c0 .84.19 1.11 1.02 1.11.84 0 1.02-.27 1.02-1.1v-2.88h.38v2.87c0 1.11-.47 1.58-1.49 1.58-.84 0-1.3-.47-1.3-1.58v-2.86h.37Zm-48.27 0v2.86c0 .84.19 1.11 1.02 1.11.84 0 1.02-.27 1.02-1.1v-2.88h.37v2.87c0 1.11-.46 1.58-1.48 1.58-.84 0-1.3-.47-1.3-1.58v-2.86h.37Zm88.93 0c.65 0 1.2.27 1.48 1.01l-.46.1c-.1-.47-.56-.65-.93-.65-.65 0-1.02.28-1.02.65 0 .46.37.55 1.02.83.93.28 1.49.46 1.49 1.3 0 .92-.84 1.2-1.49 1.2-1.02-.1-1.48-.47-1.76-1.2l.46-.1c.19.65.56.83 1.21.83.46 0 1.02-.18 1.02-.83 0-.55-.56-.74-1.11-.92-.84-.28-1.4-.37-1.4-1.2 0-.65.56-1.02 1.49-1.02Zm22.46 0 1.3 2.03 1.3-2.03h.47l-1.49 2.4v1.94h-.37v-1.94h-.19l-1.48-2.4h.46Zm-100.71 0 1.3 2.03 1.3-2.03h.46l-1.49 2.4v1.94H90v-1.94h-.18l-1.49-2.4h.47Zm99.23.09v.37h-1.4v3.88h-.37v-3.88h-1.39v-.37h3.16Zm-73.8-.1c1.3 0 1.67.84 1.67 2.04v.28c0 1.2-.46 2.03-1.67 2.03-1.2 0-1.67-.83-1.67-2.03v-.28c0-1.2.37-2.03 1.67-2.03Zm27.48 0 1.3 2.04 1.3-2.03h.46l-1.48 2.4v1.94h-.38v-1.94h-.18l-1.49-2.4h.47Zm41.77 0v4.35h-.37v-4.34h.37Zm-11.79 0c.74 0 1.3.38 1.3 1.2 0 .84-.56 1.21-1.3 1.21h-1.2v1.94h-.38v-4.34h1.58Zm-52.63 0c.74 0 1.3.38 1.3 1.12 0 .64-.37 1.01-.93 1.1l1.02 2.04h-.46l-1.02-1.94h-1.21v2.03h-.37v-4.34h1.67Zm39.36 0c.74 0 1.3.38 1.3 1.12 0 .64-.38 1.01-.93 1.1l1.02 2.04h-.47l-1.02-1.94h-1.2v2.03h-.37v-4.34h1.67Zm21.81 0c.74 0 1.3.38 1.3 1.12 0 .64-.37 1.01-.93 1.1l1.02 2.04h-.46l-1.02-1.94h-1.2v2.03h-.38v-4.34h1.67Zm-79.27 0c1.3 0 1.67.84 1.67 2.04v.28c0 1.2-.47 2.03-1.67 2.03-1.3 0-1.67-.83-1.67-2.03v-.28c0-1.2.37-2.03 1.67-2.03Zm-13.65.1v.37h-1.4v3.88h-.36v-3.88h-1.4v-.37h3.16Zm-4.55-.1v4.35h-.37v-4.34h.37Zm52.82 0v4.35h-.37v-4.34h.37Zm4.64.1v.37h-1.39v3.88h-.37v-3.88h-1.4v-.37h3.16Zm13.83-.1c.75 0 1.3.38 1.3 1.2 0 .84-.55 1.21-1.3 1.21h-1.2v1.94h-.37v-4.34h1.57Zm8.54 0c1.3 0 1.67.84 1.67 2.04v.28c0 1.2-.46 2.03-1.67 2.03-1.3 0-1.67-.83-1.67-2.03v-.28c0-1.2.37-2.03 1.67-2.03Zm-52.54 0c.75 0 1.3.38 1.3 1.2 0 .84-.55 1.21-1.3 1.21h-1.2v1.94h-.38v-4.34h1.58Zm21.08 0 1.94 3.52v-3.52h.38v4.35h-.28l-2.14-3.88v3.88h-.37v-4.34h.47Zm-25.44 0c.74 0 1.3.38 1.3 1.2 0 .84-.56 1.21-1.3 1.21h-1.2v1.94h-.38v-4.34h1.58Zm18.94.1v.37h-1.4v3.88h-.37v-3.88h-1.39v-.37h3.16Zm-52.91-.1v.38h-2.14v1.3h1.95v.36h-1.95v1.76h2.14v.37h-2.6v-4.16h2.6Zm105.26 0v.38h-2.13v1.3h1.95v.36h-1.95v1.76h2.13v.37h-2.6v-4.16h2.6Zm-14.39.38c-1.02 0-1.2.64-1.2 1.66v.28c0 .92.28 1.66 1.2 1.66.93 0 1.21-.74 1.21-1.66v-.28c0-1.02-.19-1.66-1.2-1.66Zm-48.36 0c-1.02 0-1.2.64-1.2 1.66v.28c0 .92.27 1.66 1.2 1.66.93 0 1.2-.74 1.2-1.66v-.28c0-1.02-.18-1.66-1.2-1.66Zm-39.54 0c-1.02 0-1.21.64-1.21 1.66v.28c0 .92.28 1.66 1.2 1.66.93 0 1.21-.74 1.21-1.66v-.28c0-1.02-.18-1.66-1.2-1.66Zm26.27 0c-1.02 0-1.21.64-1.21 1.66v.28c0 .92.28 1.66 1.2 1.66.94 0 1.21-.74 1.21-1.66v-.28c0-1.02-.18-1.66-1.2-1.66Zm47.43 1.3c.37 0 .74.27.74.73 0 .37-.28.74-.74.74a.72.72 0 0 1-.74-.74c0-.46.37-.74.74-.74Zm-52.82 0c.37 0 .75.27.75.73 0 .37-.28.74-.75.74-.46 0-.74-.28-.74-.74 0-.46.37-.74.74-.74Zm10.21-1.3h-1.3v1.66h1.3c.56 0 .84-.28.84-.83 0-.46-.28-.83-.84-.83Zm4.37 0h-1.3v1.66h1.3c.55 0 .83-.28.83-.83 0-.46-.28-.83-.83-.83Zm43.9 0h-1.2v1.66h1.2c.56 0 .84-.28.84-.83 0-.46-.28-.83-.84-.83Zm17.64 0h-1.2v1.66h1.2c.56 0 .84-.28.84-.83 0-.46-.28-.83-.84-.83Zm-52.63.09h-1.3v1.48h1.3c.55 0 .83-.28.83-.74 0-.46-.28-.74-.83-.74Zm39.36 0h-1.3v1.48h1.3c.55 0 .83-.28.83-.74 0-.46-.28-.74-.83-.74Zm21.81 0h-1.3v1.48h1.3c.56 0 .84-.28.84-.74 0-.46-.28-.74-.84-.74Zm-2.7-21.1c1.3 0 2.42.46 2.8 1.85l-.94.1c-.27-.93-1.02-1.21-1.85-1.21-1.3 0-1.95.46-1.95 1.3 0 .92.74 1.2 2.04 1.57 1.77.55 2.79.92 2.79 2.59 0 1.76-1.67 2.31-2.88 2.31-1.95 0-2.88-.74-3.34-2.22l.93-.1c.37 1.21 1.1 1.58 2.4 1.58.84 0 2.05-.28 2.05-1.57 0-1.11-1.11-1.39-2.23-1.76-1.57-.46-2.6-.83-2.6-2.4 0-1.2 1.03-2.04 2.79-2.04Zm-10.67 0v5.55c0 1.67.38 2.22 2.05 2.22s2.04-.55 2.04-2.22v-5.55h.74v5.55c0 2.22-.84 2.96-2.88 2.96-1.95 0-2.78-.74-2.78-2.96v-5.55h.83Zm-57.83 0v5.55c0 1.67.37 2.22 2.04 2.22 1.68 0 2.05-.55 2.05-2.22v-5.55h.74v5.55c0 2.22-.84 2.96-2.88 2.96-1.95 0-2.78-.74-2.78-2.96v-5.55h.83Zm-28.5-.1c2.51 0 3.25 1.67 3.25 3.99v.55c0 2.31-.83 4.07-3.25 4.07-2.32 0-3.15-1.66-3.15-4.07v-.46c0-2.32.65-4.07 3.15-4.07Zm46.42.1v8.42h-.84v-8.42h.84Zm-28.5 0 2.7 7.31 2.68-7.31h1.21v8.42h-.84v-7.22l-2.69 7.22h-.83l-2.7-7.22v7.22h-.74v-8.42h1.21Zm62.2 0c1.48 0 2.5.65 2.5 2.22 0 1.2-.74 1.94-1.76 2.22l1.85 3.98h-.93l-1.76-3.89h-2.32v3.89h-.83v-8.42h3.24Zm27.75.1v.73h-2.7v7.59h-.83v-7.59h-2.6v-.74h6.13Zm-53.1 0v.73h-2.69v7.59h-.84v-7.59h-2.6v-.74h6.13Zm-47.8-.1 2.69 7.31 2.69-7.31h1.2v8.42h-.83v-7.22l-2.69 7.22h-.84l-2.69-7.22v7.22h-.74v-8.42h1.2Zm30.72 0 3.72 6.75v-6.75h.74v8.42h-.65l-4.18-7.5v7.5h-.74v-8.42h1.11Zm19.59 0 2.5 3.89 2.51-3.9h.84l-2.97 4.64v3.79h-.75v-3.8l-2.97-4.62h.84Zm16.8.1v.73h-2.7v7.59h-.83v-7.59h-2.6v-.74h6.13Zm-82.43-.2c1.95 0 2.6.93 2.88 2.5l-.93.1c-.19-1.11-.65-1.85-2.04-1.85-1.95 0-2.42 1.39-2.42 3.24v.46c0 2.03.56 3.24 2.42 3.24 1.39 0 1.85-.74 2.13-1.85l.84.09c-.28 1.48-1.2 2.5-2.97 2.5-2.42.18-3.16-1.48-3.16-3.89v-.46c0-2.32.74-4.07 3.25-4.07Zm8.35.75c-1.94 0-2.4 1.39-2.4 3.24v.46c0 1.85.55 3.24 2.4 3.24 1.86.18 2.42-1.2 2.42-3.15v-.55c0-1.85-.37-3.24-2.42-3.24Zm80.2.18h-2.5v2.96h2.5c1.03 0 1.59-.64 1.59-1.48 0-.83-.47-1.48-1.58-1.48Zm-13.45-15.73c2.5 0 3.25 1.67 3.25 3.98v.56c0 2.3-.84 4.07-3.25 4.07-2.32 0-3.16-1.76-3.16-4.07v-.47c0-2.31.65-4.07 3.16-4.07Zm-8.73.1c1.95 0 2.6.83 2.88 2.3l-.93.1c-.19-1.11-.65-1.57-2.04-1.57-1.95 0-2.42 1.38-2.42 3.23v.47c0 2.03.56 3.24 2.42 3.24 1.4 0 2.23-.74 2.23-2.32v-.64h-2.32v-.84h3.15v4.44h-.56l-.18-.92c-.47.65-1.2 1.02-2.32 1.02-2.42 0-3.16-1.67-3.16-3.98V22c0-2.32.74-4.08 3.25-4.08Zm-63.86.09v.74h-2.7v7.58h-.83v-7.58h-2.6v-.74h6.13Zm3.52-.1v3.24h4.09v-3.24h.83v8.42h-.83V22H78.2v4.34h-.83v-8.42h.83Zm28.87 0v3.24h4.09v-3.24h.83v8.42h-.83V22h-4.09v4.34h-.83v-8.42h.83Zm9 0v8.42h-.83v-8.42h.84Zm14.49-.09 2.78 8.51h-.83l-.75-2.3h-3.34l-.74 2.3h-.74l2.78-8.5h.84Zm-8.45 0c1.95 0 2.6.93 2.88 2.5l-.93.1c-.18-1.12-.65-1.86-2.04-1.86-1.95 0-2.42 1.39-2.42 3.24v.46c0 2.04.56 3.24 2.42 3.24 1.4 0 1.86-.74 2.04-1.85l.84.1c-.28 1.47-1.21 2.5-2.97 2.5-2.33.18-3.07-1.58-3.07-3.9v-.46c0-2.31.74-4.07 3.25-4.07Zm-21.54 0c1.95 0 2.6.93 2.88 2.5l-.93.1c-.18-1.12-.64-1.86-2.04-1.86-1.95 0-2.41 1.39-2.41 3.24v.46c0 2.04.56 3.24 2.41 3.24 1.4 0 1.86-.74 2.04-1.85l.84.1c-.28 1.47-1.2 2.5-2.97 2.5-2.32.18-3.06-1.58-3.06-3.9v-.46c0-2.31.74-4.07 3.24-4.07Zm-9.18.1v.73h-4.18v2.6h3.8v.73h-3.8v3.52h4.18v.74h-5.02v-8.33h5.02Zm55.97.64c-1.95 0-2.41 1.39-2.41 3.24v.46c0 1.85.55 3.24 2.41 3.24 1.86.1 2.41-1.3 2.41-3.14v-.56c0-1.85-.37-3.24-2.41-3.24Zm-17.17.46-1.49 4.26h2.88l-1.4-4.26Z"></path>
                            </g>
                        </svg>
                    </a>
                </div>

                <p class="footer-address">
                    33 S. State Street<br>
                    Suite 750<br>
                    Chicago, IL 60603
                </p>

                <p class="footer-address">
                    info@cct.org<br>
                    Tel. 312-616-8000<br>
                    Fax 312-616-7955
                </p>

                <ul class="social-icons">
                    <li><a target="_blank" href="https://www.facebook.com/thechicagocommunitytrust"><i class="fab fa-facebook"></i></a></li>
                    <li><a target="_blank" href="https://twitter.com/chitrust"><i class="fab fa-twitter"></i></a></li>
                    <li><a target="_blank" href="https://www.instagram.com/thechicagocommunitytrust/"><i class="fab fa-instagram"></i></a></li>
                    <li><a target="_blank" href="https://www.youtube.com/user/thechicagocommunity"><i class="fab fa-youtube"></i></a></li>
                    <li><a target="_blank" href="https://www.linkedin.com/company/the-chicago-community-trust"><i class="fab fa-linkedin"></i></a></li>
                </ul>

            </div>

            <div class="col-lg-3">
                <div class="footer-list">
                    <a target="_blank" class="footer-list-link" href="https://www.cct.org/our-work/">
                        Our Work
                    </a>

                    <ul class="footer-sublist">
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/addressing-critical-needs/">
                                Addressing Critical Needs
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/advocating-for-policy-change/">
                                Advocating for Policy Change
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/building-collective-power/">
                                Building Collective Power
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/catalyzing-neighborhood-investment/">
                                Catalyzing Neighborhood Investment
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/connecting-philanthropy-to-impact/">
                                Connecting Philanthropy to Impact
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/growing-household-wealth/">
                                Growing Household Wealth
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/partnerships-initiatives/">
                                Partnerships &amp; Initiatives
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/our-work/">
                                Our Strategic Plan
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/the-wealth-gap/">
                                The Wealth Gap
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="footer-list">
                    <a class="footer-list-link" target="_blank" href="https://www.cct.org/philanthropy-giving/">
                        Philanthropy &amp; Giving
                    </a>

                    <ul class="footer-sublist">
                        <li>
                            <a target="_blank" href="https://www.cct.org/philanthropy-giving/getting-started/">
                                Getting Started
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/give/" id="19983">
                                Local Impact &amp; Ways to Give
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/philanthropy-giving/donors/">
                                For Donors
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/philanthropy-giving/for-professional-advisors/">
                                For Professional Advisors
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/philanthropy-giving/forms-resources/">
                                Forms &amp; Resources
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="footer-list">
                    <a class="footer-list-link" target="_blank" href="https://www.cct.org/grants/">
                        Grants
                    </a>

                    <ul class="footer-sublist">
                        <li>
                            <a target="_blank" href="https://www.cct.org/grants/opportunities/">
                                Opportunities
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/grants/what-we-fund/">
                                What We Fund
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/grants/how-to-apply/">
                                How to Apply
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="footer-list">
                    <a class="footer-list-link" target="_blank" href="https://www.cct.org/about/">
                        About Us
                    </a>

                    <ul class="footer-sublist">
                        <li>
                            <a target="_blank" href="https://www.cct.org/about/our-history/">
                                Our History
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/about/our-people/">
                                Our People
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/about/financials/">
                                Financials
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/about/annual-reports/" id="1906">
                                Annual Reports
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.cct.org/about/dei/" id="1904">
                                Diversity, Equity, Inclusion
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="footer-list">
                    <a class="footer-list-link" target="_blank" href="https://www.cct.org/about/careers/">
                        Join Our Team
                    </a>

                </div>
                <div class="footer-list">
                    <a class="footer-list-link" target="_blank" href="https://www.cct.org/contact-us/">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="offset-lg-3 col-lg-3">
                <p><a class="footer-link" target="_blank" href="https://www.cct.org/terms-of-use/">Terms of Use</a></p>
            </div>
            <div class="col-lg-3">
                <p><a class="footer-link" target="_blank" href="https://www.cct.org/privacy-policy/">Privacy Policy</a></p>
            </div>
        </div>

    </footer>

    @include('donor.layouts.power-by-footer')
</div>

