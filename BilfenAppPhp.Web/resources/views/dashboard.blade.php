<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bilfen İşe Giriş Evrak Takip Sistemi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/874e4f5961.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>
            .text-center p {
                margin-top: 20px;
                font-size: 0.875rem;
            }

            .sidebar {
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                /*background-color: #f8f9fa;*/
                background-color: #02558b;
            }

            .sidebar .nav-link {
                font-weight: 500;
            }

            .sidebar .nav-link:hover {
                font-weight: 500;
                background-color: #005d93;
            }

            .sidebar .nav-link.active {
                background-color: #005d93;
            }

            .content {
                margin-left: 250px;
                /*padding: 20px;*/
            }

            .nav-item {
                background-color: #0069aa;
            }

            .navbar {
                background-color: #02558b;
            }

            .form-control {
                width: 80%;
            }

            .custom-modal .modal-dialog {
                transform: translateY(-100%);
                transition: transform 0.4s ease-out;
            }

            .custom-modal.show .modal-dialog {
                transform: translateY(0);
            }

            .custom-modal.hide .modal-dialog {
                transform: translateY(-100%);
            }

            .pagination {
                padding: 10px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 5px;
                width: 100%;
                position: relative; /* Orta hizalamayı desteklemek için */
            }

            .pagination-controls {
                position: absolute;
                left: 50%;
                transform: translateX(-50%); /* Ortaya hizalama */
                display: flex;
                align-items: center;
            }

            button:disabled {
                background-color: #ccc;
                cursor: not-allowed;
            }

            .star {
                color: red;
            }

            .upload-container {
                max-width: 500px;
                margin: 50px auto;
                padding: 20px;
                border: 2px dashed #ddd;
                border-radius: 10px;
                text-align: center;
                background-color: #f9f9f9;
            }
            .upload-container:hover {
                border-color: #007bff;
            }
            .upload-preview {
                margin-top: 20px;
            }
            .upload-preview img {
                max-width: 100%;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .button-container {
                display: flex;
                gap: 10px;
                margin-left: auto;
            }

            .download-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
                color: #fff;
            }

            .download-btn i {
                margin-right: 10px;
                font-size: 18px;
            }

            .btn-pdf {
                background-color: #e74c3c;
            }

            .btn-pdf:hover {
                background-color: #c0392b;
            }

            .btn-excel {
                background-color: #2ecc71;
            }

            .btn-excel:hover {
                background-color: #27ae60;
            }

            .btn-word {
                background-color: #3498db;
            }

            .btn-word:hover {
                background-color: #2980b9;
            }

            .flag-icon {
                width: 20px;
                height: 15px;
                background-size: cover;
            }

            .flag-icon-tr {
                background-image: url('https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/tr.svg');
            }

            .flag-icon-en {
                background-image: url('https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/gb.svg');
            }

            .filter-modal {
                position: absolute;
                background: white;
                border: 1px solid #ccc;
                padding: 5px 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                display: none;
            }

            .filter-modal input {
                margin-right: 5px;
                padding: 5px;
            }

            .filter-modal button {
                padding: 5px;
                cursor: pointer;
            }

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
                appearance: textfield;
                width: 40px;
            }
        </style>
    </head>
    <body>
    <div class="sidebar p-3">
        <img src="/logo.jpg" width="100%"  alt=""/>
        <h6 class="bg-white text-center" style="max-height: 45px" id="pageHeader">
            İşe Giriş Evrak Takip Sistemi
        </h6>
        <ul class="nav flex-column">
            @if($roleName == "ADMIN")
                <li class="nav-item mb-2">
                    <a id="btnUser" class="nav-link active text-white" onclick="btnUsersClick()" style="cursor: pointer">Kullanıcılar</a>
                </li>

                <li class="nav-item mb-2">
                    <a id="btnRole" class="nav-link text-white" onclick="btnRolesClick()" style="cursor: pointer">Roller</a>
                </li>

                <li class="nav-item mb-2">
                    <a id="btnCountry" class="nav-link text-white" onclick="btnCountryClick()" style="cursor: pointer">Ülke Tanımları</a>
                </li>

                <li class="nav-item mb-2">
                    <a id="btnCity" class="nav-link text-white" onclick="btnCityClick()" style="cursor: pointer">İl Tanımları</a>
                </li>

                <li class="nav-item mb-2">
                    <a id="btnDistrict" class="nav-link text-white" onclick="btnDistrictClick()" style="cursor: pointer">İlçe Tanımları</a>
                </li>
            @elseif($roleName == "USER")
                <li class="nav-item mb-2">
                    <a id="btnLoadDocument" class="nav-link active text-white" onclick="btnLoadDocumentClick()" style="cursor: pointer">
                        Evrak Yükleme ve Takip
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a id="btnDocumentApprove" class="nav-link active text-white" onclick="btnDocumentApproveClick()" style="cursor: pointer">Evrak Onay ve Reddetme</a>
                </li>
            @endif
        </ul>
    </div>

    <nav class="content navbar">
        <div class="justify-content-start">
            <a class="navbar-brand text-white" style="margin-left: 15px; cursor: pointer;" onclick="btnProfileClick()">
                <img id="profileImage" width="50px" height="50px" style="border-radius: 50%; object-fit: cover;" alt="" src=""> <span id="lblHello">Merhaba</span>, {{ $userFullName }}
            </a>
        </div>

        <div class="justify-content-end d-flex me-3">
            <a id="btnLogout" class="btn text-white border" style="margin-right: 15px" onclick="logout()">Oturumu Kapat
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </nav>

    <div class="content p-3 pb-0">
        @if($roleName == "ADMIN")
            <div id="divUser">
                <h4 id="headerUsers">Kullanıcılar</h4>
                <hr />
                <form id="userForm" method="post">
                    <div class="row mb-3 ps-3 pe-3">
                        <div class="container col-12 bg-secondary-subtle d-flex align-items-center" style="height: 50px">
                            <a id="btnUserRecordList" class="btn btn-primary rounded me-2" onclick="btnRecordListClick()">Kayıt Listesi</a>
                            <button id="btnUserSave" type="submit" class="btn btn-success rounded me-2 btnSave">
                                Kaydet
                            </button>
                            <button id="btnUserSaveAs" type="submit" class="btn btn-danger rounded me-2 btnSaveAs" onclick="btnSaveAsClick()">Farklı Kaydet</button>
                            <button type="submit" class="btn btn-secondary rounded me-2" onclick="btnDeleteClick()">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <a class="btn btn-light rounded" onclick="btnResetClick()">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item bg-white" role="presentation">
                            <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true">Hesap Bilgileri</button>
                        </li>
                        <li class="nav-item bg-white" role="presentation">
                            <button class="nav-link" id="core-tab" data-bs-toggle="tab" data-bs-target="#core" type="button" role="tab" aria-controls="core" aria-selected="false">Özlük Bilgileri</button>
                        </li>
                        <li class="nav-item bg-white" role="presentation">
                            <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false">Adres Bilgileri</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="userTabs">
                        <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <div class="border p-3">
                                <div class="row mb-3">
                                    <div class="col-2">
                                        <label id="lblUserCreatedDate" class="form-label">Oluşturulma Tarihi</label>
                                    </div>

                                    <div class="col-4">
                                        <p id="userCreatedAt"></p>
                                    </div>

                                    <div class="col-2">
                                        <label id="lblUserUpdatedDate" class="form-label">Son Güncelleme Tarihi</label>
                                    </div>

                                    <div class="col-4">
                                        <p id="userUpdatedAt"></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="firstName" class="form-label" id="lblFirstName">Adı <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="text" id="firstName" class="form-control"
                                               placeholder="Kullanıcı isim bilgisini giriniz" />

                                        <span id="UserFirstName-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="lastName" class="form-label" id="lblLastName">Soyadı <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="text" id="lastName" class="form-control"
                                               placeholder="Kullanıcı soyisim bilgisini giriniz"/>

                                        <span id="UserLastName-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="userName" class="form-label" id="lblUserNameText">Kullanıcı Adı <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="text" id="userName" class="form-control"
                                               placeholder="Kullanıcı adı bilgisini giriniz" />

                                        <span id="UserUserName-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="email" class="form-label" id="lblEmailText">Email <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="email" id="email" class="form-control"
                                               placeholder="Kullanıcı email bilgisini giriniz"/>

                                        <span id="UserEmail-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="password" class="form-label" id="lblPassword">Parola <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="password" id="password" class="form-control"
                                               placeholder="Kullanıcı parola bilgisini giriniz"/>

                                        <span id="UserPassword-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                        <label for="selectedRole" class="form-label" id="lblRole">Rol <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8">
                                        <select id="selectedRole" class="form-select form-control" aria-label="Default select example">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="core" role="tabpanel" aria-labelledby="core-tab">
                            <div class="border p-3">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label class="form-label" id="lblImage">Fotoğraf</label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <div class="upload-container m-0" style="width:80%;">
                                            <h4 id="headerUploadProfilePhoto">Profil Fotoğrafı Yükle</h4>
                                            <p id="txtUploadProfilePhoto" class="text-muted">Resmi sürükleyin ve bırakın veya seç butonuna tıklayın</p>
                                            <input type="file" id="imageUpload" class="form-control d-none" accept="image/*">
                                            <button id="btnImageSelect" class="btn btn-primary" onclick="document.getElementById('imageUpload').click();">Seç</button>
                                            <button id="btnImageRemove" class="btn btn-danger" onclick="ClearImage()">Kaldır</button>
                                            <div class="upload-preview" id="uploadPreview"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="serialNumber" class="form-label" id="lblSerialNumber">TC Kimlik Numarası <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="text" id="serialNumber" class="form-control"
                                               placeholder="TC Kimlik Numarası bilgisini giriniz">

                                        <span id="UserTCKN-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="motherName" class="form-label" id="lblMother">Anne Adı <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="text" id="motherName" class="form-control"
                                               placeholder="Anne adı bilgisini giriniz" />

                                        <span id="UserMotherName-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="fatherName" class="form-label" id="lblFather">Baba Adı <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="text" id="fatherName" class="form-control"
                                               placeholder="Baba adı bilgisini giriniz"/>

                                        <span id="UserFatherName-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="birthDate" class="form-label" id="lblBirth">Doğum Tarihi <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="date" id="birthDate" class="form-control"
                                               placeholder="Doğum tarihi bilgisini giriniz" />

                                        <span id="UserBirthDate-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="gender" class="form-label" id="lblGenderText">Cinsiyet <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <select id="gender" class="form-select form-control">
                                            <option value="E">Erkek</option>
                                            <option value="K">Kadın</option>
                                        </select>

                                        <span id="UserGender-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="civilStatus" class="form-label" id="lblCivilStatusText">Medeni Hal <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <select id="civilStatus" class="form-select form-control">
                                            <option value="Evli">Evli</option>
                                            <option value="Bekar">Bekar</option>
                                        </select>

                                        <span id="UserCivilStatus-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="employmentDate" class="form-label" id="lblEmploymentDateText">İşe Giriş Tarihi <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <input type="date" id="employmentDate" class="form-control"
                                               placeholder="İşe giriş tarihi bilgisini giriniz" />

                                        <span id="UserEmploymentDate-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="militaryStatus" class="form-label" id="lblMilitaryStatusText">Askerlik Durumu</label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <select id="militaryStatus" class="form-select form-control">
                                            <option value=""></option>
                                            <option value="C">Tamamlamış</option>
                                            <option value="P">Tecilli</option>
                                            <option value="E">Muaf</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                        <label for="postponementDate" class="form-label" id="lblPostponementDate" style="display: none;">Tecil Tarihi <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8">
                                        <input type="date" id="postponementDate" class="form-control"
                                               placeholder="Tecil tarihi bilgisini giriniz" style="display: none;" />

                                        <span id="UserPostponementDate-error" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                            <div class="border p-3">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="countryId" class="form-label" id="lblCountry">Ülke <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <div class="d-flex">
                                            <input type="hidden" id="countryId">
                                            <input id="countryName" class="form-control" disabled>
                                            <a class="btn pe-0 border-0" onclick="btnSearchClick('Country')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kayıt Listesi">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </a>
                                            <a class="btn pe-0 border-0" onclick="btnSearchClearClick('Country')" data-bs-toggle="tooltip" data-bs-placement="top" title="Temizle">
                                                <i class="fa-solid fa-x"></i>
                                            </a>
                                        </div>

                                        <div id="UserCountryId-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="cityId" class="form-label" id="lblCity">İl <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <div class="d-flex">
                                            <input type="hidden" id="cityId">
                                            <input id="cityName" class="form-control" disabled>
                                            <a class="btn pe-0 border-0" onclick="btnSearchClick('City')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kayıt Listesi">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </a>
                                            <a class="btn pe-0 border-0" onclick="btnSearchClearClick('City')" data-bs-toggle="tooltip" data-bs-placement="top" title="Temizle">
                                                <i class="fa-solid fa-x"></i>
                                            </a>
                                        </div>

                                        <span id="UserCityId-error" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                        <label for="districtId" class="form-label" id="lblDistrict">İlçe <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                        <div class="d-flex">
                                            <input type="hidden" id="districtId">
                                            <input id="districtName" class="form-control" disabled style="height:37.6px;">
                                            <a class="btn pe-0 border-0" onclick="btnSearchClick('District')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kayıt Listesi">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </a>
                                            <a class="btn pe-0 border-0" onclick="btnSearchClearClick('District')" data-bs-toggle="tooltip" data-bs-placement="top" title="Temizle">
                                                <i class="fa-solid fa-x"></i>
                                            </a>
                                        </div>

                                        <span id="UserDistrictId-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                        <label for="address" class="form-label" id="lblAdres">Adres <span class="star">*</span></label>
                                    </div>

                                    <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8">
                                        <textarea id="addressText" class="form-control" placeholder="Adres bilgisini giriniz" rows="5">
                                        </textarea>

                                        <span id="UserAddress-error" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div id="divRole" style="display: none;">
                <div class="d-flex justify-content-between">
                    <h4 id="headerRoles">Roller</h4>

                    <a class="btn" onclick="btnRecordInsert(updRoleName, 'roleRecordModal', roleRecordModal)">
                        <i class="fa-solid fa-plus" style="width: 20px; height: 20px;"></i>
                    </a>
                </div>
                <hr />
                <table id="roleTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'roleTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'roleTable')"></i></th>
                            <th>Rol Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'roleTable')"></i><i class="fa-solid fa-sort" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'roleTable')"></i></th>
                            <th>Oluşturulma Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'roleTable')"></i><i class="fa-solid fa-sort" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'roleTable')"></i></th>
                            <th>Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'roleTable')"></i><i class="fa-solid fa-sort" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'roleTable')"></i></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="pagination d-flex justify-content-between align-items-center">
                    <div class="pagination-controls mx-auto d-flex align-items-center">
                        <button id="btnPreviousRole" class="btn btn-primary me-2" onclick="btnPreviousClick('roles', 'GetAllRoles', rolePageIndex, rolePageCount, btnPreviousRole, btnNextRole, 'divRole')">
                            <<
                        </button>

                        <span><span class="page">Sayfa</span> <input type="number" id="rolePageIndex" value="1" oninput="pageIndexChanged(this, rolePageCount, btnPreviousRole, btnNextRole, 'roles', 'GetAllRoles')"> / <span id="rolePageCount"></span> </span>

                        <button id="btnNextRole" class="btn btn-primary ms-2" onclick="btnNextClick('roles', 'GetAllRoles', rolePageIndex, rolePageCount, btnPreviousRole, btnNextRole, 'divRole')">
                            >>
                        </button>
                    </div>

                    <div class="button-container d-flex">
                        <button class="download-btn btn-pdf" onclick="DownloadPdf('roleTable', 'roles', 'GetAllRoles')">
                            <i class="fas fa-file-pdf me-0"></i>
                        </button>
                        <button class="download-btn btn-excel" onclick="DownloadExcel('roleTable', 'roles', 'GetAllRoles')">
                            <i class="fas fa-file-excel me-0"></i>
                        </button>
                        <button class="download-btn btn-word" onclick="DownloadWord('roleTable', 'roles', 'GetAllRoles')">
                            <i class="fas fa-file-word me-0"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="divCountry" style="display: none;">
                <div class="d-flex justify-content-between">
                    <h4 id="headerCountries">Ülkeler</h4>

                    <a class="btn" onclick="btnRecordInsert(updCountryName, 'countryRecordModal', countryRecordModal)">
                        <i class="fa-solid fa-plus" style="width: 20px; height: 20px;"></i>
                    </a>
                </div>
                <hr />
                <table id="countryTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ülke Kodu <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'countryTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'countryTable')"></i></th>
                            <th>Ülke Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'countryTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'countryTable')"></i></th>
                            <th>Oluşturulma Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'countryTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'countryTable')"></i></th>
                            <th>Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'countryTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'countryTable')"></i></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="pagination d-flex justify-content-between align-items-center">
                    <div class="pagination-controls mx-auto d-flex align-items-center">
                        <button id="btnPreviousCountry" class="btn btn-primary me-2" onclick="btnPreviousClick('countries', 'GetAllCountries', countryPageIndex, countryPageCount, btnPreviousCountry, btnNextCountry, 'divCountry')">
                            <<
                        </button>

                        <span><span class="page">Sayfa</span> <input type="number" id="countryPageIndex" value="1" oninput="pageIndexChanged(this, countryPageCount, btnPreviousCountry, btnNextCountry, 'countries', 'GetAllCountries')"> / <span id="countryPageCount"></span> </span>

                        <button id="btnNextCountry" class="btn btn-primary ms-2" onclick="btnNextClick('countries', 'GetAllCountries', countryPageIndex, countryPageCount, btnPreviousCountry, btnNextCountry, 'divCountry')">
                            >>
                        </button>
                    </div>

                    <div class="button-container d-flex">
                        <button class="download-btn btn-pdf" onclick="DownloadPdf('countryTable', 'countries', 'GetAllCountries')">
                            <i class="fas fa-file-pdf me-0"></i>
                        </button>
                        <button class="download-btn btn-excel" onclick="DownloadExcel('countryTable', 'countries', 'GetAllCountries')">
                            <i class="fas fa-file-excel me-0"></i>
                        </button>
                        <button class="download-btn btn-word" onclick="DownloadWord('countryTable', 'countries', 'GetAllCountries')">
                            <i class="fas fa-file-word me-0"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="divCity" style="display: none;">
                <div class="d-flex justify-content-between">
                    <h4 id="headerCities">Şehirler</h4>

                    <a class="btn" onclick="btnRecordInsert(updCityName, 'cityRecordModal', cityRecordModal)">
                        <i class="fa-solid fa-plus" style="width: 20px; height: 20px;"></i>
                    </a>
                </div>
                <hr />
                <table id="cityTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>İl Kodu <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'cityTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'cityTable')"></i></th>
                        <th>İl Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'cityTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'cityTable')"></i></th>
                        <th>Ülke <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'cityTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'cityTable')"></i></th>
                        <th>Oluşturulma Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'cityTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'cityTable')"></i></th>
                        <th>Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(4, this, 'cityTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(4, 'cityTable')"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="pagination d-flex justify-content-between align-items-center">
                    <div class="pagination-controls mx-auto d-flex align-items-center">
                        <button id="btnPreviousCity" class="btn btn-primary me-2" onclick="btnPreviousClick('cities', 'GetAllCities', cityPageIndex, cityPageCount, btnPreviousCity, btnNextCity, 'divCity')">
                            <<
                        </button>

                        <span><span class="page">Sayfa</span> <input type="number" id="cityPageIndex" value="1" oninput="pageIndexChanged(this, cityPageCount, btnPreviousCity, btnNextCity, 'cities', 'GetAllCities')"> / <span id="cityPageCount"></span> </span>

                        <button id="btnNextCity" class="btn btn-primary ms-2" onclick="btnNextClick('cities', 'GetAllCities', cityPageIndex, cityPageCount, btnPreviousCity, btnNextCity, 'divCity')">
                            >>
                        </button>
                    </div>

                    <div class="button-container d-flex">
                        <button class="download-btn btn-pdf" onclick="DownloadPdf('cityTable', 'cities', 'GetAllCities')">
                            <i class="fas fa-file-pdf me-0"></i>
                        </button>
                        <button class="download-btn btn-excel" onclick="DownloadExcel('cityTable', 'cities', 'GetAllCities')">
                            <i class="fas fa-file-excel me-0"></i>
                        </button>
                        <button class="download-btn btn-word" onclick="DownloadWord('cityTable', 'cities', 'GetAllCities')">
                            <i class="fas fa-file-word me-0"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="divDistrict" style="display: none;">
                <div class="d-flex justify-content-between">
                    <h4 id="headerDistricts">İlçeler</h4>

                    <a class="btn" onclick="btnRecordInsert(updDistrictName, 'districtRecordModal', districtRecordModal)">
                        <i class="fa-solid fa-plus" style="width: 20px; height: 20px;"></i>
                    </a>
                </div>
                <hr />
                <table id="districtTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>İlçe Kodu <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'districtTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'districtTable')"></i></th>
                        <th>İlçe Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'districtTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'districtTable')"></i></th>
                        <th>Şehir <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'districtTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'districtTable')"></i></th>
                        <th>Oluşturulma Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'districtTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'districtTable')"></i></th>
                        <th>Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(4, this, 'districtTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(4, 'districtTable')"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="pagination d-flex justify-content-between align-items-center">
                    <div class="pagination-controls mx-auto d-flex align-items-center">
                        <button id="btnPreviousDistrict" class="btn btn-primary me-2" onclick="btnPreviousClick('districts', 'GetAllDistricts', districtPageIndex, districtPageCount, btnPreviousDistrict, btnNextDistrict, 'divDistrict')">
                            <<
                        </button>

                        <span><span class="page">Sayfa</span> <input type="number" id="districtPageIndex" value="1" oninput="pageIndexChanged(this, districtPageCount, btnPreviousDistrict, btnNextDistrict, 'districts', 'GetAllDistricts')"> / <span id="districtPageCount"></span> </span>

                        <button id="btnNextDistrict" class="btn btn-primary ms-2" onclick="btnNextClick('districts', 'GetAllDistricts', districtPageIndex, districtPageCount, btnPreviousDistrict, btnNextDistrict, 'divDistrict')">
                            >>
                        </button>
                    </div>

                    <div class="button-container d-flex">
                        <button class="download-btn btn-pdf" onclick="DownloadPdf('districtTable', 'districts', 'GetAllDistricts')">
                            <i class="fas fa-file-pdf me-0"></i>
                        </button>
                        <button class="download-btn btn-excel" onclick="DownloadExcel('districtTable', 'districts', 'GetAllDistricts')">
                            <i class="fas fa-file-excel me-0"></i>
                        </button>
                        <button class="download-btn btn-word" onclick="DownloadWord('districtTable', 'districts', 'GetAllDistricts')">
                            <i class="fas fa-file-word me-0"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="divProfile" style="display: none;">
                <h1 class="h4">{{ $userFullName }}</h1>
                <hr>

                <div class="row">
                    <div class="col-4">
                        <img id="profilePageImage" src="" class="w-100" alt="" style="border-radius: 50%; object-fit: cover;" />
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileUserName">Kullanıcı Adı:</span>
                                <span id="lblUserName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileEmail">Email:</span>
                                <span id="lblEmail"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileMotherName">Anne Adı:</span>
                                <span id="lblMotherName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileFatherName">Baba Adı:</span>
                                <span id="lblFatherName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileRole">Rol:</span>
                                <span id="lblRoleName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileBirthDate">Doğum Tarihi:</span>
                                <span id="lblBirthDate"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileGender">Cinsiyet:</span>
                                <span id="lblGender"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCivilStatus">Medeni Hal:</span>
                                <span id="lblCivilStatus"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileEmploymentDate">İşe Alım Tarihi:</span>
                                <span id="lblEmploymentDate"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCountry">Ülke:</span>
                                <span id="lblCountryName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileMilitaryStatus">Askerlik Durumu:</span>
                                <span id="lblMilitaryStatus"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfilePostponementDate">Tecil Tarihi:</span>
                                <span id="lblPostponementDateText"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCity">İl:</span>
                                <span id="lblCityName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileDistrict">İlçe:</span>
                                <span id="lblDistrictName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileAddress">Adres:</span>
                                <span id="lblAddress"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($roleName == "USER")
            <div id="divLoadDocument">
                <h4 id="headerDocumentUpload">Evrak Yükleme ve Takip</h4>
                <hr />
                <form id="documentForm" method="post">
                    <div class="row mb-3 ps-3 pe-3">
                        <div class="container col-12 bg-secondary-subtle d-flex align-items-center" style="height: 50px">
                            <a class="btn btn-primary rounded me-2" onclick="btnRecordListClick()">Kayıt Listesi</a>
                            <button type="submit" class="btn btn-success rounded me-2 btnSave">
                                Kaydet
                            </button>
                            <button type="submit" class="btn btn-danger rounded me-2 btnSaveAs" onclick="btnSaveAsClick()">Farklı Kaydet</button>
                            <button type="submit" class="btn btn-secondary rounded me-2" onclick="btnDeleteClick()">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <a class="btn btn-light rounded" onclick="btnResetClick()">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </div>

                    <div class="border p-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                <label class="form-label" id="documentCreatedDate">Oluşturulma Tarihi</label>
                            </div>

                            <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                <p id="txtCreatedDate"></p>
                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                <label class="form-label" id="documentUpdatedDate">Son Güncelleme Tarihi</label>
                            </div>

                            <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                <p id="txtUpdatedDate"></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                <label for="documentName" class="form-label" id="lblDocumentName"
                                >Evrak Adı <span class="star">*</span></label
                                >
                            </div>

                            <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                <input type="text" id="documentName" class="form-control"
                                       placeholder="Evrak adı bilgisini giriniz"/>

                                <span id="Name-error" class="text-danger"></span>
                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                <label class="form-label" id="lblStatus">Durumu</label>
                            </div>

                            <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                <p id="txtState"></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4 mb-3">
                                <label for="document" class="form-label" id="lblDocument">Evrak <span class="star">*</span></label>
                            </div>

                            <div class="col-lg-4 col-md-8 col-sm-8 col-xs-8 mb-3">
                                <input type="file" id="document" class="form-control" onchange="handleFileChange(event)"/>
                                <p id="filePath"></p>
                                <span id="FilePath-error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div id="divProfile" style="display: none;">
                <h1 class="h4">{{ $userFullName }}</h1>
                <hr>

                <div class="row">
                    <div class="col-4">
                        <img id="profilePageImage" src="" class="w-100" alt="" style="border-radius: 50%; object-fit: cover;" />
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileUserName">Kullanıcı Adı:</span>
                                <span id="lblUserName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileEmail">Email:</span>
                                <span id="lblEmail"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileMotherName">Anne Adı:</span>
                                <span id="lblMotherName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileFatherName">Baba Adı:</span>
                                <span id="lblFatherName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileRole">Rol:</span>
                                <span id="lblRoleName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileBirthDate">Doğum Tarihi:</span>
                                <span id="lblBirthDate"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileGender">Cinsiyet:</span>
                                <span id="lblGender"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCivilStatus">Medeni Hal:</span>
                                <span id="lblCivilStatus"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileEmploymentDate">İşe Alım Tarihi:</span>
                                <span id="lblEmploymentDate"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCountry">Ülke:</span>
                                <span id="lblCountryName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileMilitaryStatus">Askerlik Durumu:</span>
                                <span id="lblMilitaryStatus"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfilePostponementDate">Tecil Tarihi:</span>
                                <span id="lblPostponementDateText"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCity">İl:</span>
                                <span id="lblCityName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileDistrict">İlçe:</span>
                                <span id="lblDistrictName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileAddress">Adres:</span>
                                <span id="lblAddress"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div id="divDocumentApprove">
                <div class="d-flex justify-content-between">
                    <h4 id="headerDocumentApproveReject">Evrak Onay ve Reddetme</h4>
                </div>
                <hr />
                <table id="documentApproveTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'documentApproveTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'documentApproveTable')"></i></th>
                            <th>Evrak Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'documentApproveTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'documentApproveTable')"></i></th>
                            <th>Kullanıcı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'documentApproveTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'documentApproveTable')"></i></th>
                            <th>Yüklenme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'documentApproveTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'documentApproveTable')"></i></th>
                            <th>Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(4, this, 'documentApproveTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(4, 'documentApproveTable')"></i></th>
                            <th>İndir</th>
                            <th>Durumu <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(6, this, 'documentApproveTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(6, 'documentApproveTable')"></i></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="pagination d-flex justify-content-between align-items-center">
                    <div class="pagination-controls mx-auto d-flex align-items-center">
                        <button id="btnPreviousDocumentApprove" class="btn btn-primary me-2" onclick="btnPreviousDocumentApproveClick()">
                            <<
                        </button>

                        <span><span class="page">Sayfa</span> <input type="number" id="documentApprovePageIndex" value="1" oninput="pageIndexChanged(this, documentApprovePageCount, btnPreviousDocumentApprove, btnNextDocumentApprove, 'documents', 'GetAllDocuments')"> / <span id="documentApprovePageCount"></span> </span>

                        <button id="btnNextDocumentApprove" class="btn btn-primary ms-2" onclick="btnNextDocumentApproveClick()">
                            >>
                        </button>
                    </div>

                    <div class="button-container d-flex">
                        <button class="download-btn btn-pdf" onclick="DownloadPdf('documentApproveTable', 'documents', 'GetAllDocuments')">
                            <i class="fas fa-file-pdf me-0"></i>
                        </button>
                        <button class="download-btn btn-excel" onclick="DownloadExcel('documentApproveTable', 'documents', 'GetAllDocuments')">
                            <i class="fas fa-file-excel me-0"></i>
                        </button>
                        <button class="download-btn btn-word" onclick="DownloadWord('documentApproveTable', 'documents', 'GetAllDocuments')">
                            <i class="fas fa-file-word me-0"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="divProfile" style="display: none;">
                <h1 class="h4">{{ $userFullName }}</h1>
                <hr>

                <div class="row">
                    <div class="col-4">
                        <img id="profilePageImage" src="" class="w-100" alt="" style="border-radius: 50%; object-fit: cover;" />
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileUserName">Kullanıcı Adı:</span>
                                <span id="lblUserName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileEmail">Email:</span>
                                <span id="lblEmail"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileMotherName">Anne Adı:</span>
                                <span id="lblMotherName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileFatherName">Baba Adı:</span>
                                <span id="lblFatherName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileRole">Rol:</span>
                                <span id="lblRoleName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileBirthDate">Doğum Tarihi:</span>
                                <span id="lblBirthDate"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileGender">Cinsiyet:</span>
                                <span id="lblGender"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCivilStatus">Medeni Hal:</span>
                                <span id="lblCivilStatus"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileEmploymentDate">İşe Alım Tarihi:</span>
                                <span id="lblEmploymentDate"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCountry">Ülke:</span>
                                <span id="lblCountryName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileMilitaryStatus">Askerlik Durumu:</span>
                                <span id="lblMilitaryStatus"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfilePostponementDate">Tecil Tarihi:</span>
                                <span id="lblPostponementDateText"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileCity">İl:</span>
                                <span id="lblCityName"></span>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileDistrict">İlçe:</span>
                                <span id="lblDistrictName"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <span class="fw-bold" id="lblProfileAddress">Adres:</span>
                                <span id="lblAddress"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button id="btnOK" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="btnOkClick()">
                        Tamam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="recordListModal" style="z-index: 1;" tabindex="-1" role="dialog" aria-labelledby="recordListModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kayıt Listesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnCloseClick()">
                    </button>
                </div>
                <div class="modal-body w-100 overflow-scroll">
                    <table id="{{ $roleName == 'ADMIN' ? 'userTable' : 'documentTable' }}" class="table table-bordered">
                        <thead>
                            @if($roleName == "ADMIN")
                                <tr>
                                    <th>Id <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'userTable')"></i></th>
                                    <th>Resim</th>
                                    <th>Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'userTable')"></i></th>
                                    <th>Soyadı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'userTable')"></i></th>
                                    <th>Kullanıcı Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(4, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(4, 'userTable')"></i></th>
                                    <th>Email <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(5, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(5, 'userTable')"></i></th>
                                    <th>Parola <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(6, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(6, 'userTable')"></i></th>
                                    <th>TC Kimlik No <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(7, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(7, 'userTable')"></i></th>
                                    <th>Rol <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(8, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(8, 'userTable')"></i></th>
                                    <th>Oluşturulma Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(9, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(9, 'userTable')"></i></th>
                                    <th>Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(10, this, 'userTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(10, 'userTable')"></i></th>
                                </tr>
                            @else
                                <tr>
                                    <th style="width: 20px">Id <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'documentTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'documentTable')"></i></th>
                                    <th style="width: 30px">Evrak Adı <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'documentTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'documentTable')"></i></th>
                                    <th style="width: 20px">Durumu <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(2, this, 'documentTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(2, 'documentTable')"></i></th>
                                    <th style="width: 30px">Oluşturulma Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(3, this, 'documentTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(3, 'documentTable')"></i></th>
                                    <th style="width: 30px">Son Güncelleme Tarihi <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(4, this, 'documentTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(4, 'documentTable')"></i></th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    @if($roleName == "ADMIN")
                        <div class="pagination d-flex justify-content-between align-items-center">
                            <div class="pagination-controls mx-auto d-flex align-items-center">
                                <button id="btnPreviousUser" class="btn btn-primary me-2" onclick="btnPreviousClick('users', 'GetAllUsers', userPageIndex, userPageCount, btnPreviousUser, btnNextUser, 'recordListModal')">
                                    <<
                                </button>

                                <span><span class="page">Sayfa</span> <input type="number" id="userPageIndex" value="1" oninput="pageIndexChanged(this, userPageCount, btnPreviousUser, btnNextUser, 'users', 'GetAllUsers')"> / <span id="userPageCount"></span> </span>

                                <button id="btnNextUser" class="btn btn-primary ms-2" onclick="btnNextClick('users', 'GetAllUsers', userPageIndex, userPageCount, btnPreviousUser, btnNextUser, 'recordListModal')">
                                    >>
                                </button>
                            </div>

                            <div class="button-container d-flex">
                                <button class="download-btn btn-pdf" onclick="DownloadPdf('userTable', 'users', 'GetAllUsers')">
                                    <i class="fas fa-file-pdf me-0"></i>
                                </button>
                                <button class="download-btn btn-excel" onclick="DownloadExcel('userTable', 'users', 'GetAllUsers')">
                                    <i class="fas fa-file-excel me-0"></i>
                                </button>
                                <button class="download-btn btn-word" onclick="DownloadWord('userTable', 'users', 'GetAllUsers')">
                                    <i class="fas fa-file-word me-0"></i>
                                </button>
                            </div>
                        </div>
                    @elseif($roleName == "USER")
                        <div class="pagination d-flex justify-content-between align-items-center">
                            <div class="pagination-controls mx-auto d-flex align-items-center">
                                <button id="btnPreviousDocument" class="btn btn-primary me-2" onclick="btnPreviousDocumentClick()">
                                    <<
                                </button>

                                <span><span class="page">Sayfa</span> <input type="number" id="documentPageIndex" value="1" oninput="pageIndexChanged(this, documentPageCount, btnPreviousDocument, btnNextDocument, 'documents', 'GetAllDocumentsByUserId')"> / <span id="documentPageCount"></span> </span>

                                <button id="btnNextDocument" class="btn btn-primary ms-2" onclick="btnNextDocumentClick()">
                                    >>
                                </button>
                            </div>

                            <div class="button-container d-flex">
                                <button class="download-btn btn-pdf" onclick="DownloadPdf('documentTable', 'documents', `GetAllDocumentsByUserId/{{ $userId }}`)">
                                    <i class="fas fa-file-pdf me-0"></i>
                                </button>
                                <button class="download-btn btn-excel" onclick="DownloadExcel('documentTable', 'documents', `GetAllDocumentsByUserId/{{ $userId }}`)">
                                    <i class="fas fa-file-excel me-0"></i>
                                </button>
                                <button class="download-btn btn-word" onclick="DownloadWord('documentTable', 'documents', `GetAllDocumentsByUserId/{{ $userId }}`)">
                                    <i class="fas fa-file-word me-0"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="roleRecordModal" style="z-index: 1;" tabindex="-1" role="dialog" aria-labelledby="roleRecordModalLabel">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnCloseClick()">
                    </button>
                </div>
                <form id="roleForm" method="post">
                    <div class="modal-body">
                        <div class="row d-flex align-items-center">
                            <div class="col-3">
                                <label for="updRoleName" id="lblRoleNameText">Rol Adı <span class="star">*</span></label>
                            </div>

                            <div class="col-9">
                                <input type="text" id="updRoleName" class="form-control"/>
                                <span id="RoleName-error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btnSave" data-bs-dismiss="modal">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="roleDeleteModal" tabindex="-1" role="dialog" aria-labelledby="roleDeleteModalLabel">
        <div class="modal-dialog" style="max-width: 500px">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title lblDelete">Sil</h5>
                </div>
                <div class="modal-body">
                    <p>
                        <span id="delRoleName"></span> isimli rol kaydını silmek istediğinize emin misiniz?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary btnDeleteYes" onclick="btnDeleteYesClick('roles', 'DeleteRole', RoleId, roleDeleteModal)">Evet</a>
                    <a class="btn btn-danger btnDeleteCancel" onclick="btnDeleteCancelClick(roleDeleteModal)">İptal</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="countryRecordModal" style="z-index: 1;" tabindex="-1" role="dialog" aria-labelledby="countryRecordModalLabel">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnCloseClick()">
                    </button>
                </div>
                <form id="countryForm" method="post">
                    <div class="modal-body">
                        <div class="row d-flex align-items-center mb-3">
                            <div class="col-4">
                                <label for="updCountryCode" id="mdlCountryCode">Ülke Kodu <span class="star">*</span></label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="updCountryCode" class="form-control"/>
                                <span id="CountryId-error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="row d-flex align-items-center">
                            <div class="col-4">
                                <label for="updCountryName" id="mdlCountryName">Ülke Adı <span class="star">*</span></label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="updCountryName" class="form-control"/>
                                <span id="CountryName-error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btnSave" data-bs-dismiss="modal">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="countryDeleteModal" tabindex="-1" role="dialog" aria-labelledby="countryDeleteModalLabel">
        <div class="modal-dialog" style="max-width: 500px">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title lblDelete">Sil</h5>
                </div>
                <div class="modal-body">
                    <p>
                        <span id="delCountryName"></span> isimli ülke kaydını silmek istediğinize emin misiniz?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary btnDeleteYes" onclick="btnDeleteYesClick('countries', 'DeleteCountry', CountryId, countryDeleteModal)">Evet</a>
                    <a class="btn btn-danger btnDeleteCancel" onclick="btnDeleteCancelClick(countryDeleteModal)">İptal</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="cityRecordModal" style="z-index: 1;" tabindex="-1" role="dialog" aria-labelledby="cityRecordModalLabel">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnCloseClick()">
                    </button>
                </div>
                <form id="cityForm" method="post">
                    <div class="modal-body">
                        <div class="row mb-3 d-flex align-items-center">
                            <div class="col-3">
                                <label for="updCityCode" id="mdlCityCode">İl Kodu <span class="star">*</span></label>
                            </div>

                            <div class="col-9">
                                <input type="text" id="updCityCode" class="form-control"/>
                                <span id="CityId-error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="row mb-3 d-flex align-items-center">
                            <div class="col-3">
                                <label for="updCityName" id="mdlCityName">İl Adı <span class="star">*</span></label>
                            </div>

                            <div class="col-9">
                                <input type="text" id="updCityName" class="form-control"/>
                                <span id="CityName-error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="row d-flex align-items-center">
                            <div class="col-3">
                                <label for="updCountryId" id="mdlCountry">Ülke <span class="star">*</span></label>
                            </div>

                            <div class="col-9 d-flex">
                                <input type="hidden" id="updCountryId">
                                <input id="popCountryName" class="form-control d-inline-block" disabled>
                                <a class="btn pe-0 border-0" onclick="btnSearchClick('CityCountry')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kayıt Listesi">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                                <a class="btn pe-0 border-0" onclick="btnSearchClearClick('CityCountry')" data-bs-toggle="tooltip" data-bs-placement="top" title="Temizle">
                                    <i class="fa-solid fa-x"></i>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-9">
                                <span id="CityCountryId-error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btnSave" data-bs-dismiss="modal">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="cityDeleteModal" tabindex="-1" role="dialog" aria-labelledby="cityDeleteModalLabel">
        <div class="modal-dialog" style="max-width: 500px">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title lblDelete">Sil</h5>
                </div>
                <div class="modal-body">
                    <p>
                        <span id="delCityName"></span> isimli il kaydını silmek istediğinize emin misiniz?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary btnDeleteYes" onclick="btnDeleteYesClick('cities', 'DeleteCity', CityId, cityDeleteModal)">Evet</a>
                    <a class="btn btn-danger btnDeleteCancel" onclick="btnDeleteCancelClick(cityDeleteModal)">İptal</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="districtRecordModal" style="z-index: 1;" tabindex="-1" role="dialog" aria-labelledby="districtRecordModalLabel">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnCloseClick()">
                    </button>
                </div>
                <form id="districtForm" method="post">
                    <div class="modal-body">
                        <div class="row mb-3 d-flex align-items-center">
                            <div class="col-4">
                                <label for="updDistrictCode" id="mdlDistrictCode">İlçe Kodu <span class="star">*</span></label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="updDistrictCode" class="form-control"/>
                                <span id="DistrictId-error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="row mb-3 d-flex align-items-center">
                            <div class="col-4">
                                <label for="updDistrictName" id="mdlDistrictName">İlçe Adı <span class="star">*</span></label>
                            </div>

                            <div class="col-8">
                                <input type="text" id="updDistrictName" class="form-control"/>
                                <span id="DistrictName-error" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="row d-flex align-items-center">
                            <div class="col-4">
                                <label for="updCityId" id="mdlCity">İl <span class="star">*</span></label>
                            </div>

                            <div class="col-8 d-flex">
                                <input type="hidden" id="updCityId">
                                <input id="popCityName" class="form-control d-inline-block" disabled>
                                <a class="btn pe-0 border-0" onclick="btnSearchClick('DistrictCity')" data-bs-toggle="tooltip" data-bs-placement="top" title="Kayıt Listesi">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                                <a class="btn pe-0 border-0" onclick="btnSearchClearClick('DistrictCity')" data-bs-toggle="tooltip" data-bs-placement="top" title="Temizle">
                                    <i class="fa-solid fa-x"></i>
                                </a>
                            </div>

                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-8">
                                    <span id="DistrictCityId-error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btnSave" data-bs-dismiss="modal">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="districtDeleteModal" tabindex="-1" role="dialog" aria-labelledby="districtDeleteModalLabel">
        <div class="modal-dialog" style="max-width: 500px">
            <div class="modal-content w-100 overflow-scroll">
                <div class="modal-header">
                    <h5 class="modal-title lblDelete">Sil</h5>
                </div>
                <div class="modal-body">
                    <p>
                        <span id="delDistrictName"></span> isimli ilçe kaydını silmek istediğinize emin misiniz?
                    </p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary btnDeleteYes" onclick="btnDeleteYesClick('districts', 'DeleteDistrict', DistrictId, districtDeleteModal)">Evet</a>
                    <a class="btn btn-danger btnDeleteCancel" onclick="btnDeleteCancelClick(districtDeleteModal)">İptal</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="searchListModal" style="z-index: 1;" tabindex="-1" role="dialog" aria-labelledby="searchListModalLabel">
        <div class="modal-dialog" style="width: 500px;">
            <div class="modal-content" style="max-height:500px; overflow:scroll;">
                <div class="modal-header">
                    <h5 class="modal-title">Kayıt Listesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="btnCloseSearchClick()">
                    </button>
                </div>
                <div class="modal-body w-100 overflow-scroll">
                    <table id="searchListTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kod <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(0, this, 'searchListTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(0, 'searchListTable')"></i></th>
                                <th>Ad <i class="fa-solid fa-filter" style="cursor: pointer; float: right; line-height: 25px;" onclick="filterTable(1, this, 'searchListTable')"></i><i class="fa-solid fa-sort me-1" style="cursor: pointer; float: right; line-height: 25px;" onclick="sortTable(1, 'searchListTable')"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

@if($roleName == "ADMIN")
    <script type="text/javascript">
        const btnUser = document.getElementById('btnUser');
        const btnRole = document.getElementById('btnRole');
        const divUser = document.getElementById('divUser');
        const divRole = document.getElementById('divRole');
        const divCountry = document.getElementById('divCountry');
        const divCity = document.getElementById('divCity');
        const divDistrict = document.getElementById('divDistrict');
        const divProfile = document.getElementById('divProfile');
        const infoModal = document.getElementById('infoModal');
        const btnOK = document.getElementById('btnOK');
        const recordListModal = document.getElementById('recordListModal');
        const roleRecordModal = document.getElementById('roleRecordModal');
        let UserId = 0;
        let UserIsDeleted = false;
        let FirstName = document.getElementById('firstName');
        let LastName = document.getElementById('lastName');
        let UserName = document.getElementById('userName');
        let Email = document.getElementById('email');
        let Password = document.getElementById('password');
        let UserCreatedAt = document.getElementById('userCreatedAt');
        let UserUpdatedAt = document.getElementById('userUpdatedAt');
        let selectedRole = document.getElementById('selectedRole');
        let btnPreviousUser = document.getElementById('btnPreviousUser');
        let btnNextUser = document.getElementById('btnNextUser');
        let btnPreviousRole = document.getElementById('btnPreviousRole');
        let btnNextRole = document.getElementById('btnNextRole');
        let roleDeleteModal = document.getElementById('roleDeleteModal');
        let userPageIndex = document.getElementById('userPageIndex');
        let userPageCount = document.getElementById('userPageCount');
        let rolePageIndex = document.getElementById('rolePageIndex');
        let rolePageCount = document.getElementById('rolePageCount');
        let RoleId = 0;
        let updRoleName = document.getElementById('updRoleName');
        let delRoleName = document.getElementById('delRoleName');
        let btnCountry = document.getElementById('btnCountry');
        let btnCity = document.getElementById('btnCity');
        let btnDistrict = document.getElementById('btnDistrict');
        const infoModalTitle = document.querySelector('#infoModal .modal-title');
        const infoModalContent = document.querySelector('#infoModal .modal-body p');
        let CountryId = "";
        let countryRecordModal = document.getElementById('countryRecordModal');
        let countryDeleteModal = document.getElementById('countryDeleteModal');
        let updCountryCode = document.getElementById('updCountryCode');
        let updCountryName = document.getElementById('updCountryName');
        let delCountryName = document.getElementById('delCountryName');
        let btnPreviousCountry = document.getElementById('btnPreviousCountry');
        let btnNextCountry = document.getElementById('btnNextCountry');
        let countryPageIndex = document.getElementById('countryPageIndex');
        let countryPageCount = document.getElementById('countryPageCount');
        let CityId = "";
        let cityRecordModal = document.getElementById('cityRecordModal');
        let updCityCode = document.getElementById('updCityCode');
        let updCityId = document.getElementById('updCityId');
        let updCityName = document.getElementById('updCityName');
        let updCountryId = document.getElementById('updCountryId');
        let btnPreviousCity = document.getElementById('btnPreviousCity');
        let btnNextCity = document.getElementById('btnNextCity');
        let cityPageIndex = document.getElementById('cityPageIndex');
        let cityPageCount = document.getElementById('cityPageCount');
        let delCityName = document.getElementById('delCityName');
        let cityDeleteModal = document.getElementById('cityDeleteModal');
        let DistrictId = "";
        let districtRecordModal = document.getElementById('districtRecordModal');
        let updDistrictCode = document.getElementById('updDistrictCode');
        let updDistrictName = document.getElementById('updDistrictName');
        let btnPreviousDistrict = document.getElementById('btnPreviousDistrict');
        let btnNextDistrict = document.getElementById('btnNextDistrict');
        let districtPageIndex = document.getElementById('districtPageIndex');
        let districtPageCount = document.getElementById('districtPageCount');
        let delDistrictName = document.getElementById('delDistrictName');
        let districtDeleteModal = document.getElementById('districtDeleteModal');
        const searchListModal = document.getElementById("searchListModal");
        const countryId = document.getElementById("countryId");
        const countryName = document.getElementById("countryName");
        const popCountryName = document.getElementById("popCountryName");
        const cityId = document.getElementById("cityId");
        const cityName = document.getElementById("cityName");
        const popCityName = document.getElementById("popCityName");
        const districtId = document.getElementById("districtId");
        const districtName = document.getElementById("districtName");
        const serialNumber = document.getElementById("serialNumber");
        const motherName = document.getElementById("motherName");
        const fatherName = document.getElementById("fatherName");
        const birthDate = document.getElementById("birthDate");
        const gender = document.getElementById("gender");
        const civilStatus = document.getElementById("civilStatus");
        const employmentDate = document.getElementById("employmentDate");
        const militaryStatus = document.getElementById("militaryStatus");
        const postponementDate = document.getElementById("postponementDate");
        const addressText = document.getElementById("addressText");
        const btnImageSelect = document.getElementById("btnImageSelect");
        const btnImageRemove = document.getElementById("btnImageRemove");
        const profileImage = document.getElementById("profileImage");
        const profilePageImage = document.getElementById("profilePageImage");
        const lblUserName = document.getElementById("lblUserName");
        const lblEmail = document.getElementById("lblEmail");
        const lblMotherName = document.getElementById("lblMotherName");
        const lblFatherName = document.getElementById("lblFatherName");
        const lblRoleName = document.getElementById("lblRoleName");
        const lblBirthDate = document.getElementById("lblBirthDate");
        const lblGender = document.getElementById("lblGender");
        const lblCivilStatus = document.getElementById("lblCivilStatus");
        const lblEmploymentDate = document.getElementById("lblEmploymentDate");
        const lblCountryName = document.getElementById("lblCountryName");
        const lblMilitaryStatus = document.getElementById("lblMilitaryStatus");
        const lblPostponementDateText = document.getElementById("lblPostponementDateText");
        const lblCityName = document.getElementById("lblCityName");
        const lblDistrictName = document.getElementById("lblDistrictName");
        const lblAddress = document.getElementById("lblAddress");
        const accountTab = document.getElementById("account-tab");
        const coreTab = document.getElementById("core-tab");
        const addressTab = document.getElementById("address-tab");
        const btnUserRecordList = document.getElementById("btnUserRecordList");
        const headerUsers = document.getElementById("headerUsers");
        const lblUserCreatedDate = document.getElementById("lblUserCreatedDate");
        const lblUserUpdatedDate = document.getElementById("lblUserUpdatedDate");
        const lblFirstName = document.getElementById("lblFirstName");
        const lblLastName = document.getElementById("lblLastName");
        const lblUserNameText = document.getElementById("lblUserNameText");
        const lblEmailText = document.getElementById("lblEmailText");
        const lblRole = document.getElementById("lblRole");
        const lblImage = document.getElementById("lblImage");
        const lblSerialNumber = document.getElementById("lblSerialNumber");
        const lblMother = document.getElementById("lblMother");
        const lblFather = document.getElementById("lblFather");
        const lblBirth = document.getElementById("lblBirth");
        const lblGenderText = document.getElementById("lblGenderText");
        const lblCivilStatusText = document.getElementById("lblCivilStatusText");
        const lblEmploymentDateText = document.getElementById("lblEmploymentDateText");
        const lblMilitaryStatusText = document.getElementById("lblMilitaryStatusText");
        const lblCountry = document.getElementById("lblCountry");
        const lblCity = document.getElementById("lblCity");
        const lblDistrict = document.getElementById("lblDistrict");
        const lblAdres = document.getElementById("lblAdres");
        const headerRoles = document.getElementById("headerRoles");
        const btnUpdateRole = document.getElementsByClassName("btnUpdateRole");
        const btnDeleteRole = document.getElementsByClassName("btnDeleteRole");
        const headerCountries = document.getElementById("headerCountries");
        const btnUpdateCountry = document.getElementsByClassName("btnUpdateCountry");
        const btnDeleteCountry = document.getElementsByClassName("btnDeleteCountry");
        const headerCities = document.getElementById("headerCities");
        const btnUpdateCity = document.getElementsByClassName("btnUpdateCity");
        const btnDeleteCity = document.getElementsByClassName("btnDeleteCity");
        const headerDistricts = document.getElementById("headerDistricts");
        const btnUpdateDistrict = document.getElementsByClassName("btnUpdateDistrict");
        const btnDeleteDistrict = document.getElementsByClassName("btnDeleteDistrict");
        const headerUploadProfilePhoto = document.getElementById("headerUploadProfilePhoto");
        const txtUploadProfilePhoto = document.getElementById("txtUploadProfilePhoto");
        const lblRoleNameText = document.getElementById("lblRoleNameText");
        const btnSave = document.getElementsByClassName("btnSave");
        const btnSaveAs = document.getElementsByClassName("btnSaveAs");
        const lblDelete = document.getElementsByClassName("lblDelete");
        const btnDeleteYes = document.getElementsByClassName("btnDeleteYes");
        const btnDeleteCancel = document.getElementsByClassName("btnDeleteCancel");
        const mdlCountryCode = document.getElementById("mdlCountryCode");
        const mdlCountryName = document.getElementById("mdlCountryName");
        const mdlCityCode = document.getElementById("mdlCityCode");
        const mdlCityName = document.getElementById("mdlCityName");
        const mdlCountry = document.getElementById("mdlCountry");
        const mdlDistrictCode = document.getElementById("mdlDistrictCode");
        const mdlDistrictName = document.getElementById("mdlDistrictName");
        const mdlCity = document.getElementById("mdlCity");
        const lblHello = document.getElementById("lblHello");
        const btnLogout = document.getElementById("btnLogout");
        const pageHeader = document.getElementById("pageHeader");
        const page = document.getElementsByClassName("page");
        const roleNameError = document.getElementById("RoleName-error");
        const countryIdError = document.getElementById("CountryId-error");
        const countryNameError = document.getElementById("CountryName-error");
        const cityIdError = document.getElementById("CityId-error");
        const cityNameError = document.getElementById("CityName-error");
        const cityCountryIdError = document.getElementById("CityCountryId-error");
        const districtIdError = document.getElementById("DistrictId-error");
        const districtNameError = document.getElementById("DistrictName-error");
        const districtCityIdError = document.getElementById("DistrictCityId-error");
        const lblProfileUserName = document.getElementById("lblProfileUserName");
        const lblProfileEmail = document.getElementById("lblProfileEmail");
        const lblProfileMotherName = document.getElementById("lblProfileMotherName");
        const lblProfileFatherName = document.getElementById("lblProfileFatherName");
        const lblProfileRole = document.getElementById("lblProfileRole");
        const lblProfileBirthDate = document.getElementById("lblProfileBirthDate");
        const lblProfileGender = document.getElementById("lblProfileGender");
        const lblProfileCivilStatus = document.getElementById("lblProfileCivilStatus");
        const lblProfileEmploymentDate = document.getElementById("lblProfileEmploymentDate");
        const lblProfileCountry = document.getElementById("lblProfileCountry");
        const lblProfileMilitaryStatus = document.getElementById("lblProfileMilitaryStatus");
        const lblProfilePostponementDate = document.getElementById("lblProfilePostponementDate");
        const lblProfileCity = document.getElementById("lblProfileCity");
        const lblProfileDistrict = document.getElementById("lblProfileDistrict");
        const lblProfileAddress = document.getElementById("lblProfileAddress");
        let ImageFilePath = "";
        let selectedFile;

        window.onload = async () => {
            debugger;
            const allUsers = await axios.get("http://localhost:8000/api/users/GetAllUsers");
            const paginatedUserData = usePagination(allUsers.data, userPageIndex.value);
            const allRoles = await axios.get("http://localhost:8000/api/roles/GetAllRoles");
            const paginatedRoleData = usePagination(allRoles.data, rolePageIndex.value);
            const allCountries = await axios.get("http://localhost:8000/api/countries/GetAllCountries");
            const paginatedCountryData = usePagination(allCountries.data, countryPageIndex.value);
            const allCities = await axios.get("http://localhost:8000/api/cities/GetAllCities");
            const paginatedCityData = usePagination(allCities.data, cityPageIndex.value);
            const allDistricts = await axios.get("http://localhost:8000/api/districts/GetAllDistricts");
            const paginatedDistrictData = usePagination(allDistricts.data, districtPageIndex.value);
            const tableCountry = document.querySelector("#divCountry table tbody");
            const tableCity = document.querySelector("#divCity table tbody");
            const tableDistrict = document.querySelector("#divDistrict table tbody");
            const tableRecordList = document.querySelector("#recordListModal table tbody");
            const tableRole = document.querySelector("#divRole table tbody");
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get("userId");
            const userData = await axios.get("http://localhost:8000/api/users/GetUser/" + userId);
            profileImage.src = userData.data.ImagePath == null ? (userData.data.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png') : userData.data.ImagePath;
            profilePageImage.src = userData.data.ImagePath == null ? (userData.data.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png') : userData.data.ImagePath;
            lblUserName.textContent = userData.data.UserName;
            lblEmail.textContent = userData.data.Email;
            lblMotherName.textContent = userData.data.MotherName;
            lblFatherName.textContent = userData.data.FatherName;
            lblRoleName.textContent = userData.data.Role.Name;
            lblBirthDate.textContent = userData.data.BirthDate;
            lblGender.textContent = userData.data.Gender;
            lblCivilStatus.textContent = userData.data.CivilStatus;
            lblEmploymentDate.textContent = userData.data.EmploymentDate;
            lblCountryName.textContent = userData.data.Country.Name;
            lblMilitaryStatus.textContent = userData.data.MilitaryStatus ?? '-';
            lblPostponementDateText.textContent = userData.data.PostponementDate ?? '-';
            lblCityName.textContent = userData.data.City.Name;
            lblDistrictName.textContent = userData.data.District.Name;
            lblAddress.textContent = userData.data.Address;
            const lblPostponementDate = document.getElementById('lblPostponementDate');
            const error = document.getElementById('UserPostponementDate-error');

            if(militaryStatus.value === 'P') {
                lblPostponementDate.style.display = 'block';
                postponementDate.style.display = 'block';
            }

            else {
                lblPostponementDate.style.display = 'none';
                postponementDate.style.display = 'none';
                error.style.display = 'none';
            }

            checkPreviousAndNextButtons(paginatedUserData, btnPreviousUser, btnNextUser);
            showData(paginatedUserData, 'recordListModal', userPageCount);
            checkPreviousAndNextButtons(paginatedRoleData, btnPreviousRole, btnNextRole);
            showData(paginatedRoleData, 'divRole', rolePageCount);
            checkPreviousAndNextButtons(paginatedCountryData, btnPreviousCountry, btnNextCountry);
            showData(paginatedCountryData, 'divCountry', countryPageCount);
            checkPreviousAndNextButtons(paginatedCityData, btnPreviousCity, btnNextCity);
            showData(paginatedCityData, 'divCity', cityPageCount);
            checkPreviousAndNextButtons(paginatedDistrictData, btnPreviousDistrict, btnNextDistrict);
            showData(paginatedDistrictData, 'divDistrict', districtPageCount);

            for (let i = 0; i < allRoles.data.length; i++){
                selectedRole.innerHTML += `
                    <option value="${ allRoles.data[i].Id }">${ allRoles.data[i].Name }</option>
                `;
            }

            checkTableEmpty(tableCountry);
            checkTableEmpty(tableCity);
            checkTableEmpty(tableDistrict);
            checkTableEmpty(tableRecordList);
            checkTableEmpty(tableRole);
        }

        const sortTable = (colIndex, tableId) => {
            debugger;
            const table = document.getElementById(tableId);
            const rows = Array.from(table.rows).slice(1);
            const isAsc = table.getAttribute('data-sort-asc') === 'true';

            rows.sort((a, b) => {
                const cellA = a.cells[colIndex].textContent.trim();
                const cellB = b.cells[colIndex].textContent.trim();
                return isAsc
                    ? cellA.localeCompare(cellB, undefined, { numeric: true })
                    : cellB.localeCompare(cellA, undefined, { numeric: true });
            });

            table.setAttribute('data-sort-asc', !isAsc);
            rows.forEach(row => table.tBodies[0].appendChild(row));
        }

        const filterTable = (colIndex, triggerElement, tableId) => {
            debugger;
            const modalId = `filter${tableId}`;
            let modalDiv = document.getElementById(modalId);

            if (!modalDiv) {
                modalDiv = document.createElement("div");
                modalDiv.id = `filter${tableId}`;
                modalDiv.style.zIndex = 2;
                modalDiv.classList = "filter-modal d-none";
                modalDiv.innerHTML = `<input type="text" style="border: 1px solid #02558B; border-radius: 5px;"
                                            id="filter${tableId}Input${colIndex}" placeholder="Değer girin">
                                      <button class="btn btn-primary" onclick="applyFilter(${colIndex}, this, '${tableId}', '${modalId}')">
                                            Ara
                                      </button>`;
                document.body.appendChild(modalDiv);
            }

            else {
                modalDiv.remove();
                modalDiv.innerHTML = `<input type="text" style="border: 1px solid #02558B; border-radius: 5px;"
                                            id="filter${tableId}Input${colIndex}" placeholder="Değer girin">
                                      <button class="btn btn-primary" onclick="applyFilter(${colIndex}, this, '${tableId}', '${modalId}')">
                                            Ara
                                      </button>`;
                document.body.appendChild(modalDiv);
            }

            const rect = triggerElement.getBoundingClientRect();
            modalDiv.style.top = `${rect.bottom + window.scrollY}px`;
            modalDiv.style.left = `${rect.left}px`;

            if(modalDiv.classList.contains("d-flex")) {
                modalDiv.classList.remove("d-flex");
                modalDiv.classList.add("d-none");
            }

            else {
                modalDiv.classList.remove("d-none");
                modalDiv.classList.add("d-flex");
            }
        }

        function applyFilter(colIndex, buttonElement, tableId, modalId) {
            debugger;
            const input = buttonElement.previousElementSibling.value.toLowerCase();
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll("tbody tr");
            const filterModal = document.getElementById(modalId);

            rows.forEach(row => {
                const col = row.cells[colIndex];

                if (col) {
                    const colText = col.textContent.toLowerCase();
                    row.style.display = colText.includes(input) ? "" : "none";
                    filterModal.classList.remove("d-flex");
                    filterModal.classList.add("d-none");
                }
            });
        }

        const pageIndexChanged = async (pageIndexInput, pageCountInput, btnPrevious, btnNext, url, method) => {
            debugger;

            if(pageIndexInput.value !== '') {
                if(pageIndexInput.value <= 0) {
                    pageIndexInput.value = 1;
                }

                if(pageIndexInput.value > pageCountInput.innerHTML) {
                    pageIndexInput.value = pageCountInput.innerHTML;
                }

                const allData = await axios.get(`http://localhost:8000/api/${url}/${method}`);
                const paginatedData = usePagination(allData.data, pageIndexInput.value);
                checkPreviousAndNextButtons(paginatedData, btnPrevious, btnNext);
                showData(paginatedData, url === 'users' ? 'recordListModal' : url === 'roles' ? 'divRole' : url === 'countries' ? 'divCountry' : url === 'cities' ? 'divCity' : url === 'districts' ? 'divDistrict' : '', pageCountInput.innerHTML);
            }
        }

        const checkTableEmpty = (table) => {
            if(!table.querySelector("tr")) {
                table.parentNode.querySelector('thead').remove();
                const tr = document.createElement("tr");
                const td = document.createElement("td");
                td.textContent = "Listelenecek bir kayıt bulunamadı.";
                td.colSpan = 5;
                tr.appendChild(td);
                table.appendChild(tr);
            }
        }

        document.getElementById("userForm").addEventListener("submit", async function (event) {
            debugger;
            event.preventDefault();
            const firstName = FirstName.value;
            const lastName = LastName.value;
            const userName = UserName.value;
            const email = Email.value;
            const password = Password.value;
            const selRole = selectedRole.value;
            const SerialNumber = serialNumber.value;
            const MotherName = motherName.value;
            const FatherName = fatherName.value;
            const BirthDate = birthDate.value;
            const Gender = gender.value;
            const CivilStatus = civilStatus.value;
            const EmploymentDate = employmentDate.value;
            const MilitaryStatus = militaryStatus.value;
            const PostponementDate = postponementDate.value;
            const CountryId = countryId.value;
            const CityId = cityId.value;
            const DistrictId = districtId.value;
            const AddressText = addressText.value;
            let res;

            try {
                if(UserIsDeleted) {
                    const res = await axios.delete(
                        `http://localhost:8000/api/users/DeleteUser/${UserId}`
                    );

                    await SetInfoMessageTitle(res);
                    infoModalContent.textContent = res.data.message;
                    infoModal.style.display = "block";
                    const allUsers = await axios.get("http://localhost:8000/api/users/GetAllUsers");
                    const paginatedUserData = usePagination(allUsers.data, userPageIndex.value);
                    checkPreviousAndNextButtons(paginatedUserData, btnPreviousUser, btnNextUser);
                    userPageCount.textContent = Math.ceil(allUsers.data.length / paginatedUserData.pageSize);
                    showData(paginatedUserData, 'recordListModal', userPageCount.textContent);
                }

                else {
                    try {
                        if (UserId === 0) {
                            res = await axios.post('http://localhost:8000/api/users/CreateUser', {
                                FirstName: firstName,
                                LastName: lastName,
                                UserName: userName,
                                Email: email,
                                Password: password,
                                RoleId: selRole,
                                ImagePath: ImageFilePath,
                                TCKN: SerialNumber,
                                MotherName: MotherName,
                                FatherName: FatherName,
                                BirthDate: BirthDate,
                                Gender: Gender,
                                CivilStatus: CivilStatus,
                                EmploymentDate: EmploymentDate,
                                MilitaryStatus: MilitaryStatus,
                                PostponementDate: PostponementDate,
                                CountryId: CountryId,
                                CityId: CityId,
                                DistrictId: DistrictId,
                                Address: AddressText,
                            });
                        } else {
                            res = await axios.put("http://localhost:8000/api/users/EditUser", {
                                Id: UserId,
                                FirstName: firstName,
                                LastName: lastName,
                                UserName: userName,
                                Email: email,
                                Password: password,
                                updatedAt: UserUpdatedAt.value,
                                RoleId: selRole,
                                ImagePath: ImageFilePath,
                                TCKN: serialNumber.value,
                                MotherName: motherName.value,
                                FatherName: fatherName.value,
                                BirthDate: birthDate.value,
                                Gender: gender.value,
                                CivilStatus: civilStatus.value,
                                EmploymentDate: employmentDate.value,
                                MilitaryStatus: militaryStatus.value,
                                PostponementDate: postponementDate.value,
                                CountryId: countryId.value,
                                CityId: cityId.value,
                                DistrictId: districtId.value,
                                Address: addressText.value,
                            });
                        }

                        ClearUserForm();
                        await UploadImage();
                        await SetInfoModalContent(res);
                        infoModal.style.display = "block";
                        const allUsers = await axios.get("http://localhost:8000/api/users/GetAllUsers");
                        const paginatedUserData = usePagination(allUsers.data, userPageIndex.value);
                        checkPreviousAndNextButtons(paginatedUserData, btnPreviousUser, btnNextUser);
                        userPageCount.textContent = Math.ceil(allUsers.data.length / paginatedUserData.pageSize);
                        showData(paginatedUserData, 'recordListModal', userPageCount.textContent);
                    }

                    catch(error)
                    {
                        if (error.response && error.response.status === 422) {
                            const validationErrors = error.response.data.errors;
                            displayValidationErrors(validationErrors, ['FirstName', 'LastName', 'UserName', 'Email', 'Password', 'RoleId', 'TCKN', 'MotherName', 'FatherName', 'BirthDate', 'Gender', 'CivilStatus', 'EmploymentDate', 'PostponementDate', 'CountryId', 'CityId', 'DistrictId', 'Address'], 'User');
                        }
                    }
                }
            } catch (err) {
                console.error(err.message);
            }
        });

        document.getElementById("roleForm").addEventListener("submit", async function (event) {
            debugger;
            event.preventDefault();
            const roleName = updRoleName.value;
            let res;

            try {
                try {
                    let allRoles;

                    if (RoleId === 0) {
                        res = await axios.post('http://localhost:8000/api/roles/CreateRole', {
                            Name: roleName
                        });

                        allRoles = await axios.get("http://localhost:8000/api/roles/GetAllRoles");
                        rolePageIndex.value = Math.ceil(allRoles.data.length / 5);
                    } else {
                        res = await axios.put("http://localhost:8000/api/roles/EditRole", {
                            Id: RoleId,
                            Name: roleName
                        });

                        allRoles = await axios.get("http://localhost:8000/api/roles/GetAllRoles");
                    }

                    await SetInfoModalContent(res);
                    roleRecordModal.style.display = "none";
                    infoModal.style.display = "block";
                    const paginatedRoleData = usePagination(allRoles.data, rolePageIndex.value);
                    checkPreviousAndNextButtons(paginatedRoleData, btnPreviousRole, btnNextRole);
                    rolePageCount.textContent = Math.ceil(allRoles.data.length / paginatedRoleData.pageSize);
                    showData(paginatedRoleData, 'divRole', rolePageCount.textContent);
                }

                catch(error)
                {
                    if (error.response && error.response.status === 422) {
                        const validationErrors = error.response.data.errors;
                        displayValidationErrors(validationErrors, ['Name'], 'Role');
                    }
                }
            } catch (err) {
                console.error(err.message);
            }
        });

        document.getElementById("countryForm").addEventListener("submit", async function (event) {
            debugger;
            event.preventDefault();
            const countryCode = updCountryCode.value;
            const countryName = updCountryName.value;
            let res;

            try {
                try {
                    let allCountries;

                    if (CountryId === "") {
                        res = await axios.post('http://localhost:8000/api/countries/CreateCountry', {
                            Id: countryCode,
                            Name: countryName
                        });

                        allCountries = await axios.get("http://localhost:8000/api/countries/GetAllCountries");
                        countryPageIndex.value = Math.ceil(allCountries.data.length / 5);
                    } else {
                        res = await axios.put("http://localhost:8000/api/countries/EditCountry", {
                            Id: CountryId,
                            Name: countryName
                        });

                        allCountries = await axios.get("http://localhost:8000/api/countries/GetAllCountries");
                    }

                    await SetInfoModalContent(res);
                    ClearCountryForm();
                    countryRecordModal.style.display = "none";
                    infoModal.style.display = "block";
                    const paginatedCountryData = usePagination(allCountries.data, countryPageIndex.value);
                    checkPreviousAndNextButtons(paginatedCountryData, btnPreviousCountry, btnNextCountry);
                    countryPageCount.textContent = Math.ceil(allCountries.data.length / paginatedCountryData.pageSize);
                    showData(paginatedCountryData, 'divCountry', countryPageCount.textContent);
                }

                catch(error)
                {
                    if (error.response && error.response.status === 422) {
                        const validationErrors = error.response.data.errors;
                        displayValidationErrors(validationErrors, ['Id', 'Name'], 'Country');
                    }
                }
            } catch (err) {
                console.error(err.message);
            }
        });

        document.getElementById("cityForm").addEventListener("submit", async function (event) {
            debugger;
            event.preventDefault();
            const cityCode = updCityCode.value;
            const cityName = updCityName.value;
            const countryId = updCountryId.value;
            let res;

            try {
                try {
                    let allCities;

                    if (CityId === "") {
                        res = await axios.post('http://localhost:8000/api/cities/CreateCity', {
                            Id: cityCode,
                            Name: cityName,
                            CountryId: countryId
                        });

                        allCities = await axios.get("http://localhost:8000/api/cities/GetAllCities");
                        cityPageIndex.value = Math.ceil(allCities.data.length / 5);
                    } else {
                        res = await axios.put("http://localhost:8000/api/cities/EditCity", {
                            Id: CityId,
                            Name: cityName,
                            CountryId: countryId
                        });

                        allCities = await axios.get("http://localhost:8000/api/cities/GetAllCities");
                    }

                    await SetInfoModalContent(res);
                    ClearCityForm();
                    cityRecordModal.style.display = "none";
                    infoModal.style.display = "block";
                    const paginatedCityData = usePagination(allCities.data, cityPageIndex.value);
                    checkPreviousAndNextButtons(paginatedCityData, btnPreviousCity, btnNextCity);
                    cityPageCount.textContent = Math.ceil(allCities.data.length / paginatedCityData.pageSize);
                    showData(paginatedCityData, 'divCity', cityPageCount.textContent);
                }

                catch(error)
                {
                    if (error.response && error.response.status === 422) {
                        const validationErrors = error.response.data.errors;
                        displayValidationErrors(validationErrors, ['Id', 'Name', 'CountryId'], 'City');
                    }
                }
            } catch (err) {
                console.error(err.message);
            }
        });

        document.getElementById("districtForm").addEventListener("submit", async function (event) {
            debugger;
            event.preventDefault();
            const districtCode = updDistrictCode.value;
            const districtName = updDistrictName.value;
            const cityId = updCityId.value;
            let res;

            try {
                try {
                    let allDistricts;

                    if (DistrictId === "") {
                        res = await axios.post('http://localhost:8000/api/districts/CreateDistrict', {
                            Id: districtCode,
                            Name: districtName,
                            CityId: cityId
                        });

                        allDistricts = await axios.get("http://localhost:8000/api/districts/GetAllDistricts");
                        districtPageIndex.value = Math.ceil(allDistricts.data.length / 5);
                    } else {
                        res = await axios.put("http://localhost:8000/api/districts/EditDistrict", {
                            Id: DistrictId,
                            Name: districtName,
                            CityId: cityId
                        });

                        allDistricts = await axios.get("http://localhost:8000/api/districts/GetAllDistricts");
                    }

                    await SetInfoModalContent(res);
                    ClearDistrictForm();
                    districtRecordModal.style.display = "none";
                    infoModal.style.display = "block";
                    const paginatedDistrictData = usePagination(allDistricts.data, districtPageIndex.value);
                    checkPreviousAndNextButtons(paginatedDistrictData, btnPreviousDistrict, btnNextDistrict);
                    districtPageCount.textContent = Math.ceil(allDistricts.data.length / paginatedDistrictData.pageSize);
                    showData(paginatedDistrictData, 'divDistrict', districtPageCount.textContent);
                }

                catch(error)
                {
                    if (error.response && error.response.status === 422) {
                        const validationErrors = error.response.data.errors;
                        displayValidationErrors(validationErrors, ['Id', 'Name', 'CityId'], 'District');
                    }
                }
            } catch (err) {
                console.error(err.message);
            }
        });

        const displayValidationErrors = (errors, fields, tableName) => {
            debugger;
            for (const field in errors) {
                const fieldError = document.querySelector(`#${tableName}${field}-error`);

                if (fieldError) {
                    fieldError.style.display = "block";
                    fieldError.textContent = errors[field][0];
                }
            }

            const errorFields = Object.keys(errors);
            const correctFields = fields.filter(item => !errorFields.includes(item));

            for(let i = 0; i < correctFields.length; i++) {
                const fieldError = document.querySelector(`#${tableName}${correctFields[i]}-error`);

                if (fieldError) {
                    fieldError.style.display = "none";
                }
            }
        }

        const ClearUserForm = () => {
            FirstName.value = "";
            document.getElementById("UserFirstName-error").style.display = "none";
            LastName.value = "";
            document.getElementById("UserLastName-error").style.display = "none";
            UserName.value = "";
            document.getElementById("UserUserName-error").style.display = "none";
            Email.value = "";
            document.getElementById("UserEmail-error").style.display = "none";
            Password.value = "";
            document.getElementById("UserPassword-error").style.display = "none";
            selectedRole.value = "1";
            serialNumber.value = "";
            document.getElementById("UserTCKN-error").style.display = "none";
            motherName.value = "";
            document.getElementById("UserMotherName-error").style.display = "none";
            fatherName.value = "";
            document.getElementById("UserFatherName-error").style.display = "none";
            birthDate.value = "";
            document.getElementById("UserBirthDate-error").style.display = "none";
            gender.value = "E";
            document.getElementById("UserGender-error").style.display = "none";
            civilStatus.value = "Evli";
            document.getElementById("UserCivilStatus-error").style.display = "none";
            employmentDate.value = "";
            document.getElementById("UserEmploymentDate-error").style.display = "none";
            militaryStatus.value = "";
            postponementDate.value = "";
            const lblPostponementDate = document.getElementById('lblPostponementDate');
            lblPostponementDate.style.display = "none";
            postponementDate.style.display = "none";
            document.getElementById("UserPostponementDate-error").style.display = "none";
            countryId.value = "";
            countryName.value = "";
            document.getElementById("UserCountryId-error").style.display = "none";
            cityId.value = "";
            cityName.value = "";
            document.getElementById("UserCityId-error").style.display = "none";
            districtId.value = "";
            districtName.value = "";
            document.getElementById("UserDistrictId-error").style.display = "none";
            addressText.value = "";
            document.getElementById("UserAddress-error").style.display = "none";
            UserCreatedAt.textContent = "";
            UserUpdatedAt.textContent = "";
            ImageFilePath = "";
            uploadPreview.innerHTML = "";
        }

        const ClearCountryForm = () => {
            updCountryCode.value = "";
            updCountryName.value = "";
        }

        const ClearCityForm = () => {
            updCityCode.value = "";
            updCityName.value = "";
            updCountryId.value = "";
            popCountryName.value = "";
        }

        const ClearDistrictForm = () => {
            updDistrictCode.value = "";
            updDistrictName.value = "";
            updCityId.value = "";
            popCityName.value = "";
        }

        const usePagination = (data, pageIndex) => {
            let page = { val: pageIndex }
            const pageSize = 5;
            const totalItems = data.length;
            const totalPages = Math.ceil(totalItems / pageSize);

            const paginatedData = () => {
                return data.slice(
                    (page.val - 1) * pageSize,
                    page.val * pageSize
                );
            };

            const changePage = (newPage) => {
                if (newPage >= 1 && newPage <= totalPages) {
                    page.val = newPage;
                }
            };

            return {
                page,
                pageSize,
                totalItems,
                totalPages,
                paginatedData,
                changePage,
            };
        }

        const btnPreviousClick = async (url, method, pageIndex, pageCount, btnPrevious, btnNext, id) => {
            debugger;
            const allData = await axios.get(`http://localhost:8000/api/${url}/${method}`);
            const paginatedData = usePagination(allData.data, pageIndex.value);
            pageIndex.value = (Number(pageIndex.value) - 1).toString();
            paginatedData.changePage(pageIndex.value);

            checkPreviousAndNextButtons(paginatedData, btnPrevious, btnNext);
            showData(paginatedData, id, pageCount);
        }

        const btnNextClick = async (url, method, pageIndex, pageCount, btnPrevious, btnNext, id) => {
            debugger;
            const allData = await axios.get(`http://localhost:8000/api/${url}/${method}`);
            const paginatedData = usePagination(allData.data, pageIndex.value);
            pageIndex.value = (Number(pageIndex.value) + 1).toString();
            paginatedData.changePage(pageIndex.value);

            checkPreviousAndNextButtons(paginatedData, btnPrevious, btnNext);
            showData(paginatedData, id, pageCount);
        }

        const checkPreviousAndNextButtons = (paginatedData, btnPrevious, btnNext) => {
            debugger;
            if(paginatedData.page.val <= 1) {
                btnPrevious.classList.remove("btn-primary");
                btnPrevious.classList.add("btn-dark");
                btnPrevious.classList.add("disabled");
            }

            else {
                btnPrevious.classList.remove("btn-dark");
                btnPrevious.classList.add("btn-primary");
                btnPrevious.classList.remove("disabled");
            }

            if(paginatedData.page.val >= paginatedData.totalPages) {
                btnNext.classList.remove("btn-primary");
                btnNext.classList.add("btn-dark");
                btnNext.classList.add("disabled");
            }

            else {
                btnNext.classList.remove("btn-dark");
                btnNext.classList.add("btn-primary");
                btnNext.classList.remove("disabled");
            }
        }

        const showData = (paginatedData, id, pageCount) => {
            debugger;
            const tbody = document.querySelector(`#${id} table tbody`);
            tbody.innerHTML = '';

            if(id === 'recordListModal') {
                paginatedData.paginatedData().forEach(user => {
                    tbody.innerHTML += `
                    <tr style="cursor: pointer" onclick="recordListRowClick(${user.Id}, '${user.FirstName}', '${user.LastName}', '${user.UserName}',
                                                '${user.Email}', '${user.Password}', '${user.RoleId}', '${user.createdAt}',
                                                '${user.updatedAt}', '${user.TCKN}', '${user.MotherName}', '${user.FatherName}',
                                                '${user.BirthDate}', '${user.Gender}', '${user.CivilStatus}', '${user.EmploymentDate}',
                                                '${user.MilitaryStatus}', '${user.PostponementDate}', '${user.CountryId}', '${user.Country.Name}', '${user.CityId}', '${user.City.Name}', '${user.DistrictId}', '${user.District.Name}', '${user.Address}', '${user.ImagePath}')">
                        <td>${user.Id}</td>
                        <td>${user.ImagePath ? `<img src="${user.ImagePath}" width="100px" height="100px" alt="Kullanıcı Fotoğrafı" />` : user.Gender === 'Erkek'
                            ? `<img src="/storage/img/Man.png" width="100px" height="100px" alt="Varsayılan Erkek Fotoğrafı" />`
                            : `<img src="/storage/img/Woman.png" width="100px" height="100px" alt="Varsayılan Kadın Fotoğrafı" />`}
                        </td>
                        <td>${user.FirstName}</td>
                        <td>${user.LastName}</td>
                        <td>${user.UserName}</td>
                        <td>${user.Email}</td>
                        <td>${user.Password}</td>
                        <td>${user.TCKN}</td>
                        <td>${user.Role.Name}</td>
                        <td>${user.createdAt}</td>
                        <td>${user.updatedAt}</td>
                    </tr>
                    `;
                });
            }

            else if (id === 'divRole') {
                paginatedData.paginatedData().forEach(role => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${role.Id}</td>
                        <td>${role.Name}</td>
                        <td>${role.createdAt}</td>
                        <td>${role.updatedAt}</td>
                        <td>
                            <a class="btn me-2 btnUpdateRole ${ role.Name === 'ADMIN' || role.Name === 'HR' || role.Name === 'USER' ? 'btn-dark disabled' : 'btn-primary' }" onclick="btnRecordUpdate('roles', 'GetRole', updRoleName, 'roleRecordModal', roleRecordModal, ${role.Id})">Güncelle</a>
                            <a class="btn btnDeleteRole ${ role.Name === 'ADMIN' || role.Name === 'HR' || role.Name === 'USER' ? 'btn-dark disabled' : 'btn-danger' }" onclick="btnRecordDelete('roles', 'GetRole', ${role.Id}, delRoleName, roleDeleteModal)">Sil</a>
                        </td>
                    </tr>
                    `;
                });
            }

            else if(id === 'divCountry') {
                paginatedData.paginatedData().forEach(country => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${country.Id}</td>
                        <td>${country.Name}</td>
                        <td>${country.createdAt}</td>
                        <td>${country.updatedAt}</td>
                        <td>
                            <a class="btn me-2 btn-primary btnUpdateCountry" onclick="btnRecordUpdate('countries', 'GetCountry', updCountryName, 'countryRecordModal', countryRecordModal, '${country.Id}')">Güncelle</a>
                            <a class="btn btn-danger btnDeleteCountry" onclick="btnRecordDelete('countries', 'GetCountry', '${country.Id}', delCountryName, countryDeleteModal)">Sil</a>
                        </td>
                    </tr>
                    `;
                });
            }

            else if(id === 'divCity') {
                paginatedData.paginatedData().forEach(city => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${city.Id}</td>
                        <td>${city.Name}</td>
                        <td>${city.Country.Name}</td>
                        <td>${city.createdAt}</td>
                        <td>${city.updatedAt}</td>
                        <td>
                            <a class="btn me-2 btn-primary btnUpdateCity" onclick="btnRecordUpdate('cities', 'GetCity', updCityName, 'cityRecordModal', cityRecordModal, '${city.Id}')">Güncelle</a>
                            <a class="btn btn-danger btnDeleteCity" onclick="btnRecordDelete('cities', 'GetCity', '${city.Id}', delCityName, cityDeleteModal)">Sil</a>
                        </td>
                    </tr>
                    `;
                });
            }

            else if(id === 'divDistrict') {
                paginatedData.paginatedData().forEach(district => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${district.Id}</td>
                        <td>${district.Name}</td>
                        <td>${district.City.Name}</td>
                        <td>${district.createdAt}</td>
                        <td>${district.updatedAt}</td>
                        <td>
                            <a class="btn me-2 btn-primary btnUpdateDistrict" onclick="btnRecordUpdate('districts', 'GetDistrict', updDistrictName, 'districtRecordModal', districtRecordModal, '${district.Id}')">Güncelle</a>
                            <a class="btn btn-danger btnDeleteDistrict" onclick="btnRecordDelete('districts', 'GetDistrict', '${district.Id}', delDistrictName, districtDeleteModal)">Sil</a>
                        </td>
                    </tr>
                    `;
                });
            }

            pageCount.textContent = paginatedData.totalPages;
        }

        const btnUsersClick = () => {
            if (btnUser && btnRole && btnCountry && btnCity && btnDistrict && divUser && divRole && divCountry && divCity && divDistrict) {
                btnUser.classList.add("active");
                btnRole.classList.remove("active");
                btnCountry.classList.remove("active");
                btnCity.classList.remove("active");
                btnDistrict.classList.remove("active");
                divUser.style.display = "block";
                divRole.style.display = "none";
                divCountry.style.display = "none";
                divCity.style.display = "none";
                divDistrict.style.display = "none";
                divProfile.style.display = "none";
            }
        };

        const btnRolesClick = () => {
            if (btnUser && btnRole && btnCountry && btnCity && btnDistrict && divUser && divRole && divCountry && divCity && divDistrict) {
                btnUser.classList.remove("active");
                btnRole.classList.add("active");
                btnCountry.classList.remove("active");
                btnCity.classList.remove("active");
                btnDistrict.classList.remove("active");
                divUser.style.display = "none";
                divRole.style.display = "block";
                divCountry.style.display = "none";
                divCity.style.display = "none";
                divDistrict.style.display = "none";
                divProfile.style.display = "none";
            }
        };

        const btnCountryClick = () => {
            if (btnUser && btnRole && btnCountry && btnCity && btnDistrict && divUser && divRole && divCountry && divCity && divDistrict) {
                btnUser.classList.remove("active");
                btnRole.classList.remove("active");
                btnCountry.classList.add("active");
                btnCity.classList.remove("active");
                btnDistrict.classList.remove("active");
                divUser.style.display = "none";
                divRole.style.display = "none";
                divCountry.style.display = "block";
                divCity.style.display = "none";
                divDistrict.style.display = "none";
                divProfile.style.display = "none";
            }
        };

        const btnCityClick = () => {
            if (btnUser && btnRole && btnCountry && btnCity && btnDistrict && divUser && divRole && divCountry && divCity && divDistrict) {
                btnUser.classList.remove("active");
                btnRole.classList.remove("active");
                btnCountry.classList.remove("active");
                btnCity.classList.add("active");
                btnDistrict.classList.remove("active");
                divUser.style.display = "none";
                divRole.style.display = "none";
                divCountry.style.display = "none";
                divCity.style.display = "block";
                divDistrict.style.display = "none";
                divProfile.style.display = "none";
            }
        };

        const btnDistrictClick = () => {
            if (btnUser && btnRole && btnCountry && btnCity && btnDistrict && divUser && divRole && divCountry && divCity && divDistrict) {
                btnUser.classList.remove("active");
                btnRole.classList.remove("active");
                btnCountry.classList.remove("active");
                btnCity.classList.remove("active");
                btnDistrict.classList.add("active");
                divUser.style.display = "none";
                divRole.style.display = "none";
                divCountry.style.display = "none";
                divCity.style.display = "none";
                divDistrict.style.display = "block";
                divProfile.style.display = "none";
            }
        };

        const btnProfileClick = () => {
            divUser.style.display = "none";
            divRole.style.display = "none";
            divCountry.style.display = "none";
            divCity.style.display = "none";
            divDistrict.style.display = "none";
            divProfile.style.display = "block";
        }

        document.getElementById('militaryStatus').addEventListener('change', function () {
            const postponementDate = document.getElementById('postponementDate');
            const lblPostponementDate = document.getElementById('lblPostponementDate');
            const error = document.getElementById('UserPostponementDate-error');

            if (this.value === 'P') {
                postponementDate.style.display = 'block';
                lblPostponementDate.style.display = 'block';
            } else {
                postponementDate.style.display = 'none';
                lblPostponementDate.style.display = 'none';
                error.style.display = 'none';
            }
        });


        const btnOkClick = () => {
            if (infoModal) {
                infoModal.style.display = "none";
            }
        };

        const btnSaveAsClick = () => {
            UserId = 0;
        }

        const btnDeleteClick = () => {
            UserIsDeleted = true;
            ClearUserForm();
        };

        const btnRecordListClick = () => {
            if (recordListModal) {
                recordListModal.style.display = "block";
            }
        };

        const btnCloseClick = () => {
            recordListModal.style.display = "none";
            roleRecordModal.style.display = "none";
            countryRecordModal.style.display = "none";
            cityRecordModal.style.display = "none";
            districtRecordModal.style.display = "none";
        };

        const btnCloseSearchClick = () => {
            searchListModal.style.display = "none";
        };

        const btnResetClick = () => {
            window.location.reload();
        };

        const recordListRowClick = (id, firstName, lastName, userName, email, password, roleId, createdAt, updatedAt, tckn,
                                    MotherName, FatherName, BirthDate, Gender, CivilStatus, EmploymentDate, MilitaryStatus,
                                    PostponementDate, CountryId, CountryName, CityId, CityName, DistrictId, DistrictName, Address, ImagePath) => {
            debugger;
            UserId = id;
            FirstName.value = firstName;
            LastName.value = lastName;
            UserName.value = userName;
            Email.value = email;
            Password.value = password;
            UserCreatedAt.textContent = createdAt;
            UserUpdatedAt.textContent = updatedAt;
            selectedRole.value = roleId.toString();
            serialNumber.value = tckn;
            motherName.value = MotherName;
            fatherName.value = FatherName;
            let dateParts = BirthDate.split(' ')[0].split('.');
            birthDate.value = new Date(`${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`).toISOString().split('T')[0];
            gender.value = Gender === 'Erkek' ? 'E' : 'K';
            civilStatus.value = CivilStatus;
            dateParts = EmploymentDate.split(' ')[0].split('.');
            employmentDate.value = new Date(`${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`).toISOString().split('T')[0];
            militaryStatus.value = MilitaryStatus === 'Tamamlamış' ? 'C' : MilitaryStatus === 'Tecilli' ? 'P' : MilitaryStatus === 'Muaf' ? 'E' : null;

            if(PostponementDate !== 'null') {
                dateParts = PostponementDate.split(' ')[0].split('.');
                postponementDate.value = new Date(`${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`).toISOString().split('T')[0];
            }

            else {
                postponementDate.value = "";
            }

            countryId.value = CountryId;
            countryName.value = CountryName;
            cityId.value = CityId;
            cityName.value = CityName;
            districtId.value = DistrictId;
            districtName.value = DistrictName;
            addressText.value = Address;
            uploadPreview.innerHTML = `<img src="${ImagePath}" alt="">`;
            ImageFilePath = ImagePath;
            recordListModal.style.display = "none";
        };

        const ClearModalFormErrors = () => {
            roleNameError.style.display = "none";
            countryIdError.style.display = "none";
            countryNameError.style.display = "none";
            cityIdError.style.display = "none";
            cityNameError.style.display = "none";
            cityCountryIdError.style.display = "none";
            districtIdError.style.display = "none";
            districtNameError.style.display = "none";
            districtCityIdError.style.display = "none";
        }

        const btnRecordInsert = (updName, id, recordModal) => {
            debugger;
            RoleId = 0;
            CountryId = "";
            CityId = "";
            DistrictId = "";
            updName.value = "";
            ClearModalFormErrors();

            if(id === 'countryRecordModal') {
                document.querySelector('#countryForm .row:first-of-type').classList.add("d-flex");
                document.querySelector('#countryForm .row:first-of-type').classList.remove("d-none");
            }

            else if(id === 'cityRecordModal') {
                document.querySelector('#cityForm .row:first-of-type').classList.add("d-flex");
                document.querySelector('#cityForm .row:first-of-type').classList.remove("d-none");
            }

            else if(id === 'districtRecordModal') {
                document.querySelector('#districtForm .row:first-of-type').classList.add("d-flex");
                document.querySelector('#districtForm .row:first-of-type').classList.remove("d-none");
            }

            const recordModalTitle = document.querySelector(`#${id} .modal-dialog .modal-content .modal-header .modal-title`);

            if (recordModal) {
                recordModalTitle.textContent = "Yeni Kayıt";
                recordModal.style.display = "block";
            }
        };

        const btnRecordUpdate = async (url, method, updName, divId, recordModal, id) => {
            debugger;
            const updatedData = await axios.get(`http://localhost:8000/api/${url}/${method}/${id}`);
            ClearModalFormErrors();

            if(divId === 'roleRecordModal'){
                RoleId = updatedData.data.Id;
            }

            else if(divId === 'countryRecordModal') {
                CountryId = updatedData.data.Id;
                document.querySelector('#countryForm .row:first-of-type').classList.remove("d-flex");
                document.querySelector('#countryForm .row:first-of-type').classList.add("d-none");
            }

            else if(divId === 'cityRecordModal') {
                CityId = updatedData.data.Id;
                document.querySelector('#cityForm .row:first-of-type').classList.remove("d-flex");
                document.querySelector('#cityForm .row:first-of-type').classList.add("d-none");
            }

            else if(divId === 'districtRecordModal') {
                DistrictId = updatedData.data.Id;
                document.querySelector('#districtForm .row:first-of-type').classList.remove("d-flex");
                document.querySelector('#districtForm .row:first-of-type').classList.add("d-none");
            }

            updName.value = updatedData.data.Name;
            const recordModalTitle = document.querySelector(`#${divId} .modal-dialog .modal-content .modal-header .modal-title`);

            if (recordModal) {
                recordModalTitle.textContent = "Güncelle";

                if(divId === 'cityRecordModal') {
                    updCountryId.value = updatedData.data.CountryId;
                    popCountryName.value = updatedData.data.Country.Name;
                }

                else if(divId === 'districtRecordModal') {
                    updCityId.value = updatedData.data.CityId;
                    popCityName.value = updatedData.data.City.Name;
                }

                recordModal.style.display = "block";
            }
        };

        const btnRecordDelete = async (url, method, id, delName, deleteModal) => {
            debugger;
            if (deleteModal) {
                let deletedData = await axios.get(`http://localhost:8000/api/${url}/${method}/${id}`);

                if(url === 'roles') {
                    RoleId = id;
                }

                else if(url === 'users') {
                    UserId = id;
                }

                else if(url === 'countries') {
                    CountryId = id;
                }

                else if(url === 'cities') {
                    CityId = id;
                }

                else if(url === 'districts') {
                    DistrictId = id;
                }

                delName.textContent = deletedData.data.Name;
                deleteModal.style.display = "block";
            }
        };

        const btnDeleteYesClick = async (url, method, id, modal) => {
            debugger;
            let res = await axios.delete(
                `http://localhost:8000/api/${url}/${method}/${id}`
            );

            infoModalTitle.textContent = "Bilgi";
            infoModalContent.textContent = res.data.message;
            modal.style.display = "none";
            infoModal.style.display = "block";

            if(url === 'roles') {
                rolePageIndex.value = 1;
                let allRoles = await axios.get(`http://localhost:8000/api/roles/GetAllRoles`);
                const paginatedData = usePagination(allRoles.data, rolePageIndex.value);
                checkPreviousAndNextButtons(paginatedData, btnPreviousRole, btnNextRole);
                rolePageCount.textContent = Math.ceil(allRoles.data.length / paginatedData.pageSize);
                showData(paginatedData, 'divRole', rolePageCount.textContent);
            }

            else if(url === 'countries') {
                countryPageIndex.value = 1;
                let allCountries = await axios.get(`http://localhost:8000/api/countries/GetAllCountries`);
                const paginatedData = usePagination(allCountries.data, countryPageIndex.value);
                checkPreviousAndNextButtons(paginatedData, btnPreviousCountry, btnNextCountry);
                countryPageCount.textContent = Math.ceil(allCountries.data.length / paginatedData.pageSize);
                showData(paginatedData, 'divCountry', countryPageCount.textContent);
            }

            else if(url === 'cities') {
                cityPageIndex.value = 1;
                let allCities = await axios.get(`http://localhost:8000/api/cities/GetAllCities`);
                const paginatedData = usePagination(allCities.data, cityPageIndex.value);
                checkPreviousAndNextButtons(paginatedData, btnPreviousCity, btnNextCity);
                cityPageCount.textContent = Math.ceil(allCities.data.length / paginatedData.pageSize);
                showData(paginatedData, 'divCity', cityPageCount.textContent);
            }

            else if(url === 'districts') {
                districtPageIndex.value = 1;
                let allDistricts = await axios.get(`http://localhost:8000/api/districts/GetAllDistricts`);
                const paginatedData = usePagination(allDistricts.data, districtPageIndex.value);
                checkPreviousAndNextButtons(paginatedData, btnPreviousDistrict, btnNextDistrict);
                districtPageCount.textContent = Math.ceil(allDistricts.data.length / paginatedData.pageSize);
                showData(paginatedData, 'divDistrict', districtPageCount.textContent);
            }
        };

        const btnDeleteCancelClick = (modal) => {
            modal.style.display = "none";
        };

        const SetInfoMessageTitle = async (res) => {
            if (res.data.code === 204) {
                infoModalTitle.textContent = "Hata";
            } else {
                infoModalTitle.textContent = "Bilgi";
            }
        };

        const SetInfoModalContent = async (res) => {
            if (res.data.message != null) {
                infoModalTitle.textContent = "Hata";
                infoModalContent.textContent = res.data.message;
            } else {
                infoModalTitle.textContent = "Bilgi";
                infoModalContent.textContent = "Kayıt işlemi başarıyla yapıldı!";
            }
        };

        const btnSearchClick = async (searchType) => {
            debugger;
            const allCountries = await axios.get('http://localhost:8000/api/countries/GetAllCountries');
            const allCities = await axios.get('http://localhost:8000/api/cities/GetAllCities');
            let allCitiesByCountryId;
            let allDistrictsByCityId;

            if(countryId.value !== '') {
                allCitiesByCountryId = await axios.get('http://localhost:8000/api/cities/GetAllCitiesByCountryId/' + countryId.value);
            }

            if(cityId.value !== '') {
                allDistrictsByCityId = await axios.get('http://localhost:8000/api/districts/GetAllDistrictsByCityId/' + cityId.value);
            }

            const tbody = document.querySelector("#searchListModal table tbody");
            tbody.innerHTML = "";

            if(searchType === "Country") {
                if (!allCountries.data || allCountries.data.length === 0) {
                    const tr = document.createElement("tr");
                    const td = document.createElement("td");
                    td.textContent = "Listelenecek bir kayıt bulunamadı.";
                    td.colSpan = 2;
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                }

                allCountries.data.forEach((item) => {
                    const tr = document.createElement("tr");
                    tr.style.cursor = "pointer";

                    tr.addEventListener("click", () => {
                        countryId.value = item.Id;
                        countryName.value = item.Name;
                        searchListModal.style.display = "none";
                    });

                    const codeColumn = document.createElement("td");
                    codeColumn.textContent = item.Id;
                    tr.appendChild(codeColumn);

                    const nameColumn = document.createElement("td");
                    nameColumn.textContent = item.Name;
                    tr.appendChild(nameColumn);

                    tbody.appendChild(tr);
                });
            }

            else if(searchType === "City") {
                if(allCitiesByCountryId !== undefined) {
                    if (!allCitiesByCountryId.data || allCitiesByCountryId.data.length === 0) {
                        ShowEmptyRow(tbody);
                    }

                    allCitiesByCountryId.data.forEach((item) => {
                        const tr = document.createElement("tr");
                        tr.style.cursor = "pointer";

                        tr.addEventListener("click", () => {
                            cityId.value = item.Id;
                            cityName.value = item.Name;
                            searchListModal.style.display = "none";
                        });

                        const codeColumn = document.createElement("td");
                        codeColumn.textContent = item.Id;
                        tr.appendChild(codeColumn);

                        const nameColumn = document.createElement("td");
                        nameColumn.textContent = item.Name;
                        tr.appendChild(nameColumn);

                        tbody.appendChild(tr);
                    });
                }

                else {
                    ShowEmptyRow(tbody);
                }
            }

            else if(searchType === "District") {
                if(allDistrictsByCityId !== undefined) {
                    if (!allDistrictsByCityId.data || allDistrictsByCityId.data.length === 0) {
                        ShowEmptyRow(tbody);
                    }

                    allDistrictsByCityId.data.forEach((item) => {
                        const tr = document.createElement("tr");
                        tr.style.cursor = "pointer";

                        tr.addEventListener("click", () => {
                            districtId.value = item.Id;
                            districtName.value = item.Name;
                            searchListModal.style.display = "none";
                        });

                        const codeColumn = document.createElement("td");
                        codeColumn.textContent = item.Id;
                        tr.appendChild(codeColumn);

                        const nameColumn = document.createElement("td");
                        nameColumn.textContent = item.Name;
                        tr.appendChild(nameColumn);

                        tbody.appendChild(tr);
                    });
                }

                else {
                    ShowEmptyRow(tbody);
                }
            }

            else if(searchType === "CityCountry") {
                if (!allCountries.data || allCountries.data.length === 0) {
                    const tr = document.createElement("tr");
                    const td = document.createElement("td");
                    td.textContent = "Listelenecek bir kayıt bulunamadı.";
                    td.colSpan = 2;
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                }

                allCountries.data.forEach((item) => {
                    const tr = document.createElement("tr");
                    tr.style.cursor = "pointer";

                    tr.addEventListener("click", () => {
                        updCountryId.value = item.Id;
                        popCountryName.value = item.Name;
                        searchListModal.style.display = "none";
                    });

                    const codeColumn = document.createElement("td");
                    codeColumn.textContent = item.Id;
                    tr.appendChild(codeColumn);

                    const nameColumn = document.createElement("td");
                    nameColumn.textContent = item.Name;
                    tr.appendChild(nameColumn);

                    tbody.appendChild(tr);
                });
            }

            else if(searchType === "DistrictCity") {
                if (!allCities.data || allCities.data.length === 0) {
                    const tr = document.createElement("tr");
                    const td = document.createElement("td");
                    td.textContent = "Listelenecek bir kayıt bulunamadı.";
                    td.colSpan = 2;
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                }

                allCities.data.forEach((item) => {
                    const tr = document.createElement("tr");
                    tr.style.cursor = "pointer";

                    tr.addEventListener("click", () => {
                        updCityId.value = item.Id;
                        popCityName.value = item.Name;
                        searchListModal.style.display = "none";
                    });

                    const codeColumn = document.createElement("td");
                    codeColumn.textContent = item.Id;
                    tr.appendChild(codeColumn);

                    const nameColumn = document.createElement("td");
                    nameColumn.textContent = item.Name;
                    tr.appendChild(nameColumn);

                    tbody.appendChild(tr);
                });
            }

            searchListModal.style.display = "block";
        }

        const btnSearchClearClick = (searchType) => {
            if(searchType === "Country") {
                countryId.value = "";
                countryName.value = "";
                cityId.value = "";
                cityName.value = "";
                districtId.value = "";
                districtName.value = "";
            }

            else if(searchType === "City") {
                cityId.value = "";
                cityName.value = "";
                districtId.value = "";
                districtName.value = "";
            }

            else if(searchType === "District") {
                districtId.value = "";
                districtName.value = "";
            }

            else if(searchType === "CityCountry") {
                updCountryId.value = "";
                popCountryName.value = "";
            }

            else if(searchType === "DistrictCity") {
                updCityId.value = "";
                popCityName.value = "";
            }
        }

        const ShowEmptyRow = (tbody) => {
            const tr = document.createElement("tr");
            const td = document.createElement("td");
            td.textContent = "Listelenecek bir kayıt bulunamadı.";
            td.colSpan = 2;
            tr.appendChild(td);
            tbody.appendChild(tr);
        }

        const logout = () => {
            Cookies.remove("token");
            Cookies.remove("isAuthenticated");
            Cookies.remove("userId");
            window.location.pathname = "/login";
        };

        const imageUpload = document.getElementById('imageUpload');
        const uploadPreview = document.getElementById('uploadPreview');

        imageUpload.addEventListener('change', function () {
            debugger;
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    uploadPreview.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image">`;
                };

                reader.readAsDataURL(file);
                ImageFilePath = "/storage/img/" + file.name;
                selectedFile = file;
            }
        });

        const UploadImage = async () => {
            debugger;
            if (selectedFile) {
                const formData = new FormData();
                formData.append("file", selectedFile);
                formData.append("directoryName", 'img');

                try {
                    const response = await fetch("http://localhost:3000/api/upload", {
                        method: "POST",
                        body: formData
                    });

                    const result = await response.json();
                    console.log("Upload result:", result);
                } catch (error) {
                    console.error("Upload error:", error);
                }
            }
        };

        btnImageSelect.addEventListener('click', function (event) {
            event.preventDefault();
        });

        btnImageRemove.addEventListener('click', function (event) {
            event.preventDefault();
        });

        const ClearImage = () => {
            uploadPreview.innerHTML = '';
            ImageFilePath = '';
        }

        const createAndFillTable = async (tableId, apiUrl) => {
            try {
                debugger;
                const response = await axios.get(apiUrl);
                const data = response.data;

                const newTable = document.createElement('table');
                newTable.id = tableId + "Data";
                newTable.className = 'table table-striped';
                const tableHead = document.createElement('thead');

                if(tableId === 'userTable') {
                    tableHead.innerHTML = `
                        <tr>
                            <th>Id</th>
                            <th>Resim</th>
                            <th>Adı</th>
                            <th>Soyadı</th>
                            <th>Kullanıcı Adı</th>
                            <th>Email</th>
                            <th>Parola</th>
                            <th>TC Kimlik No</th>
                            <th>Rol</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Son Güncelleme Tarihi</th>
                        </tr>
                    `;
                }

                else if(tableId === 'roleTable') {
                    tableHead.innerHTML = `
                        <tr>
                            <th>Id</th>
                            <th>Rol Adı</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Son Güncelleme Tarihi</th>
                        </tr>
                    `;
                }

                else if(tableId === 'countryTable') {
                    tableHead.innerHTML = `
                        <tr>
                            <th>Ülke Kodu</th>
                            <th>Ülke Adı</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Son Güncelleme Tarihi</th>
                        </tr>
                    `;
                }

                else if(tableId === 'cityTable') {
                    tableHead.innerHTML = `
                        <tr>
                            <th>İl Kodu</th>
                            <th>İl Adı</th>
                            <th>Ülke</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Son Güncelleme Tarihi</th>
                        </tr>
                    `;
                }

                else if(tableId === 'districtTable') {
                    tableHead.innerHTML = `
                        <tr>
                            <th>İlçe Kodu</th>
                            <th>İlçe Adı</th>
                            <th>Şehir</th>
                            <th>Oluşturulma Tarihi</th>
                            <th>Son Güncelleme Tarihi</th>
                        </tr>
                    `;
                }

                newTable.appendChild(tableHead);
                const tableBody = document.createElement('tbody');

                data.forEach((item) => {
                    const row = document.createElement('tr');

                    if(tableId === 'userTable') {
                        row.innerHTML = `
                            <td>${item.Id}</td>
                            <td><img src="${item.ImagePath != null ? item.ImagePath: item.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png'}" style="width: 100px; height: 100px;" alt=""></td>
                            <td>${item.FirstName}</td>
                            <td>${item.LastName}</td>
                            <td>${item.UserName}</td>
                            <td>${item.Email}</td>
                            <td>${item.Password}</td>
                            <td>${item.TCKN}</td>
                            <td>${item.Role.Name}</td>
                            <td>${item.createdAt}</td>
                            <td>${item.updatedAt}</td>
                        `;
                    }

                    else if(tableId === 'roleTable') {
                        row.innerHTML = `
                            <td>${ item.Id }</td>
                            <td>${ item.Name }</td>
                            <td>${ item.createdAt }</td>
                            <td>${ item.updatedAt }</td>
                        `;
                    }

                    else if(tableId === 'countryTable') {
                        row.innerHTML = `
                            <td>${ item.Id }</td>
                            <td>${ item.Name }</td>
                            <td>${ item.createdAt }</td>
                            <td>${ item.updatedAt }</td>
                        `;
                    }

                    else if(tableId === 'cityTable') {
                        row.innerHTML = `
                            <td>${ item.Id }</td>
                            <td>${ item.Name }</td>
                            <td>${ item.Country.Name }</td>
                            <td>${ item.createdAt }</td>
                            <td>${ item.updatedAt }</td>
                        `;
                    }

                    else if(tableId === 'districtTable') {
                        row.innerHTML = `
                            <td>${ item.Id }</td>
                            <td>${ item.Name }</td>
                            <td>${ item.City.Name }</td>
                            <td>${ item.createdAt }</td>
                            <td>${ item.updatedAt }</td>
                        `;
                    }

                    else if(tableId === 'dictionaryTable') {
                        row.innerHTML = `
                            <td>${ item.Id }</td>
                            <td>${ item.Text }</td>
                            <td>${ item.createdAt }</td>
                            <td>${ item.updatedAt }</td>
                        `;
                    }

                    tableBody.appendChild(row);
                });

                newTable.appendChild(tableBody);
                const container = document.createElement('div');
                container.style.display = "none";

                if (container) {
                    container.appendChild(newTable);
                    document.body.appendChild(container);
                } else {
                    console.error('No container found to append the table.');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        const DownloadPdf = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const element = document.getElementById(tableId + 'Data');

            const options = {
                width: 1200,
                margin: 1,
                filename: tableId === 'userTable' ? 'Users.pdf' : tableId === 'roleTable' ? 'Roles.pdf' : tableId === 'countryTable' ? 'Countries.pdf' : tableId === 'cityTable' ? 'Cities.pdf' : tableId === 'districtTable' ? 'Districts.pdf' : tableId === 'documentApproveTable' ? 'DocumentApproves.pdf' : tableId === 'documentTable' ? 'Document.pdf' : tableId === 'dictionaryTable' ? 'Dictionaries.pdf' : '',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    windowWidth: document.body.scrollWidth,
                    windowHeight: document.body.scrollHeight
                },
                jsPDF: {
                    unit: 'mm',
                    format: ['297', '420'],
                    orientation: 'landscape'
                }
            };

            html2pdf().set(options).from(element).save();
        }

        const DownloadExcel = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const table = document.getElementById(tableId + 'Data');
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(table);

            const images = table.querySelectorAll('img');

            for (let i = 0; i < images.length; i++) {
                const img = images[i];
                const imageUrl = img.src;

                const cellAddress = `B${i + 2}`;

                ws[cellAddress] = {
                    t: 's',
                    v: 'Image Link',
                    l: { Target: imageUrl },
                };
            }

            XLSX.utils.book_append_sheet(wb, ws, 'Tablo');
            XLSX.writeFile(wb, tableId === 'userTable' ? 'Users.xlsx' : tableId === 'roleTable' ? 'Roles.xlsx' : tableId === 'countryTable' ? 'Countries.xlsx' : tableId === 'cityTable' ? 'Cities.xlsx' : tableId === 'districtTable' ? 'Districts.xlsx' : tableId === 'documentApproveTable' ? 'DocumentApproves.xlsx' : tableId === 'documentTable' ? 'Document.xlsx' : tableId === 'dictionaryTable' ? 'Dictionaries.xlsx' : '');
        }

        const DownloadWord = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const table = document.getElementById(tableId + 'Data');

            const html = `
            <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40' lang="">
                <head>
                    <meta charset='utf-8'>
                    <title>${tableId === 'userTable' ? 'Kullanıcılar' : ''}</title>
                    <style>
                        @page {
                            size: 16.54in 11.69in;
                            margin: 20mm 20mm 20mm 20mm;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            table-layout: fixed;
                        }
                        th, td {
                            padding: 5px;
                            text-align: left;
                            border: 1px solid black;
                            word-wrap: break-word;
                        }
                        img {
                            max-width: 100px;
                            max-height: 100px;
                        }
                    </style>
                </head>
                <body>${table.outerHTML}</body>
            </html>`;

            const blob = new Blob(['\ufeff', html], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
            const objUrl = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = objUrl;
            link.download = tableId === 'userTable' ? 'Users.doc' : tableId === 'roleTable' ? 'Roles.doc' : tableId === 'countryTable' ? 'Countries.doc' : tableId === 'cityTable' ? 'Cities.doc' : tableId === 'districtTable' ? 'Districts.doc' : tableId === 'documentApproveTable' ? 'DocumentApproves.doc' : tableId === 'documentTable' ? 'Document.doc' : tableId === 'dictionaryTable' ? 'Dictionaries.doc' : '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };
    </script>
@elseif($roleName == "USER")
    <script type="text/javascript">
        let DocumentId = 0;
        let DocumentIsDeleted = false;
        let DocumentName = document.getElementById('documentName');
        let FilePath = document.getElementById('filePath');
        let txtState = document.getElementById('txtState');
        let txtCreatedDate = document.getElementById('txtCreatedDate');
        let txtUpdatedDate = document.getElementById('txtUpdatedDate');
        const recordListModal = document.getElementById('recordListModal');
        let documentPageIndex = document.getElementById('documentPageIndex');
        let documentPageCount = document.getElementById('documentPageCount');
        let btnPreviousDocument = document.getElementById('btnPreviousDocument');
        let btnNextDocument = document.getElementById('btnNextDocument');
        let divLoadDocument = document.getElementById('divLoadDocument');
        const infoModalTitle = document.querySelector('#infoModal .modal-title');
        const infoModalContent = document.querySelector('#infoModal .modal-body p');
        const lblUserName = document.getElementById("lblUserName");
        const lblEmail = document.getElementById("lblEmail");
        const lblMotherName = document.getElementById("lblMotherName");
        const lblFatherName = document.getElementById("lblFatherName");
        const lblRoleName = document.getElementById("lblRoleName");
        const lblBirthDate = document.getElementById("lblBirthDate");
        const lblGender = document.getElementById("lblGender");
        const lblCivilStatus = document.getElementById("lblCivilStatus");
        const lblEmploymentDate = document.getElementById("lblEmploymentDate");
        const lblCountryName = document.getElementById("lblCountryName");
        const lblMilitaryStatus = document.getElementById("lblMilitaryStatus");
        const lblPostponementDateText = document.getElementById("lblPostponementDateText");
        const lblCityName = document.getElementById("lblCityName");
        const lblDistrictName = document.getElementById("lblDistrictName");
        const lblAddress = document.getElementById("lblAddress");
        const profileImage = document.getElementById("profileImage");
        const profilePageImage = document.getElementById("profilePageImage");
        const btnLoadDocument = document.getElementById("btnLoadDocument");
        const headerDocumentUpload = document.getElementById("headerDocumentUpload");
        const documentCreatedDate = document.getElementById("documentCreatedDate");
        const documentUpdatedDate = document.getElementById("documentUpdatedDate");
        const lblDocumentName = document.getElementById("lblDocumentName");
        const lblStatus = document.getElementById("lblStatus");
        const lblDocument = document.getElementById("lblDocument");
        const lblHello = document.getElementById("lblHello");
        const page = document.getElementsByClassName("page");
        const columnStatus = document.getElementsByClassName("columnStatus");
        const lblProfileUserName = document.getElementById("lblProfileUserName");
        const lblProfileEmail = document.getElementById("lblProfileEmail");
        const lblProfileMotherName = document.getElementById("lblProfileMotherName");
        const lblProfileFatherName = document.getElementById("lblProfileFatherName");
        const lblProfileRole = document.getElementById("lblProfileRole");
        const lblProfileBirthDate = document.getElementById("lblProfileBirthDate");
        const lblProfileGender = document.getElementById("lblProfileGender");
        const lblProfileCivilStatus = document.getElementById("lblProfileCivilStatus");
        const lblProfileEmploymentDate = document.getElementById("lblProfileEmploymentDate");
        const lblProfileCountry = document.getElementById("lblProfileCountry");
        const lblProfileMilitaryStatus = document.getElementById("lblProfileMilitaryStatus");
        const lblProfilePostponementDate = document.getElementById("lblProfilePostponementDate");
        const lblProfileCity = document.getElementById("lblProfileCity");
        const lblProfileDistrict = document.getElementById("lblProfileDistrict");
        const lblProfileAddress = document.getElementById("lblProfileAddress");
        const btnOK = document.getElementById("btnOK");
        const btnSaveAs = document.getElementsByClassName("btnSaveAs");
        let selectedFile;

        window.onload = async () => {
            debugger;
            const allDocuments = await axios.get(`http://localhost:8000/api/documents/GetAllDocumentsByUserId/${ Cookies.get("userId") }`);
            const paginatedDocumentData = usePagination(allDocuments.data, documentPageIndex.value);
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get("userId");
            const userData = await axios.get("http://localhost:8000/api/users/GetUser/" + userId);
            lblUserName.textContent = userData.data.UserName;
            lblEmail.textContent = userData.data.Email;
            lblMotherName.textContent = userData.data.MotherName;
            lblFatherName.textContent = userData.data.FatherName;
            lblRoleName.textContent = userData.data.Role.Name;
            lblBirthDate.textContent = userData.data.BirthDate;
            lblGender.textContent = userData.data.Gender;
            lblCivilStatus.textContent = userData.data.CivilStatus;
            lblEmploymentDate.textContent = userData.data.EmploymentDate;
            lblCountryName.textContent = userData.data.Country.Name;
            lblMilitaryStatus.textContent = userData.data.MilitaryStatus ?? '-';
            lblPostponementDateText.textContent = userData.data.PostponementDate ?? '-';
            lblCityName.textContent = userData.data.City.Name;
            lblDistrictName.textContent = userData.data.District.Name;
            lblAddress.textContent = userData.data.Address;
            profileImage.src = userData.data.ImagePath == null ? (userData.data.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png') : userData.data.ImagePath;
            profilePageImage.src = userData.data.ImagePath == null ? (userData.data.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png') : userData.data.ImagePath;
            const tableLoadDocument = document.querySelector("#recordListModal table tbody");
            checkPreviousAndNextDocumentButtons(paginatedDocumentData);
            showDocumentData(paginatedDocumentData);
            checkTableEmpty(tableLoadDocument);
        }

        const sortTable = (colIndex, tableId) => {
            debugger;
            const table = document.getElementById(tableId);
            const rows = Array.from(table.rows).slice(1);
            const isAsc = table.getAttribute('data-sort-asc') === 'true';

            rows.sort((a, b) => {
                const cellA = a.cells[colIndex].textContent.trim();
                const cellB = b.cells[colIndex].textContent.trim();
                return isAsc
                    ? cellA.localeCompare(cellB, undefined, { numeric: true })
                    : cellB.localeCompare(cellA, undefined, { numeric: true });
            });

            table.setAttribute('data-sort-asc', !isAsc);
            rows.forEach(row => table.tBodies[0].appendChild(row));
        }

        const filterTable = (colIndex, triggerElement, tableId) => {
            debugger;
            const modalId = `filter${tableId}`;
            let modalDiv = document.getElementById(modalId);

            if (!modalDiv) {
                modalDiv = document.createElement("div");
                modalDiv.id = `filter${tableId}`;
                modalDiv.style.zIndex = 2;
                modalDiv.classList = "filter-modal d-none";
                modalDiv.innerHTML = `<input type="text" style="border: 1px solid #02558B; border-radius: 5px;"
                                            id="filter${tableId}Input${colIndex}" placeholder="Değer girin">
                                      <button class="btn btn-primary" onclick="applyFilter(${colIndex}, this, '${tableId}', '${modalId}')">
                                            Ara
                                      </button>`;
                document.body.appendChild(modalDiv);
            }

            else {
                modalDiv.remove();
                modalDiv.innerHTML = `<input type="text" style="border: 1px solid #02558B; border-radius: 5px;"
                                            id="filter${tableId}Input${colIndex}" placeholder="Değer girin">
                                      <button class="btn btn-primary" onclick="applyFilter(${colIndex}, this, '${tableId}', '${modalId}')">
                                            Ara
                                      </button>`;
                document.body.appendChild(modalDiv);
            }

            const rect = triggerElement.getBoundingClientRect();
            modalDiv.style.top = `${rect.bottom + window.scrollY}px`;
            modalDiv.style.left = `${rect.left}px`;

            if(modalDiv.classList.contains("d-flex")) {
                modalDiv.classList.remove("d-flex");
                modalDiv.classList.add("d-none");
            }

            else {
                modalDiv.classList.remove("d-none");
                modalDiv.classList.add("d-flex");
            }
        }

        function applyFilter(colIndex, buttonElement, tableId, modalId) {
            debugger;
            const input = buttonElement.previousElementSibling.value.toLowerCase();
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll("tbody tr");
            const filterModal = document.getElementById(modalId);

            rows.forEach(row => {
                const col = row.cells[colIndex];

                if (col) {
                    const colText = col.textContent.toLowerCase();
                    row.style.display = colText.includes(input) ? "" : "none";
                    filterModal.classList.remove("d-flex");
                    filterModal.classList.add("d-none");
                }
            });
        }

        const checkTableEmpty = (table) => {
            if(!table.querySelector("tr")) {
                table.parentNode.querySelector('thead').remove();
                const tr = document.createElement("tr");
                const td = document.createElement("td");
                td.textContent = "Listelenecek bir kayıt bulunamadı.";
                td.colSpan = 5;
                tr.appendChild(td);
                table.appendChild(tr);
            }
        }

        document.getElementById("documentForm").addEventListener("submit", async function (event) {
            debugger;
            event.preventDefault();
            const documentName = DocumentName.value;
            const filePath = FilePath.textContent;
            const status = "OB";
            const userId = Cookies.get("userId");
            let res;

            try {
                if(DocumentIsDeleted) {
                    const res = await axios.delete(
                        `http://localhost:8000/api/documents/DeleteDocument/${DocumentId}`
                    );

                    await SetInfoMessageTitle(res);
                    infoModalContent.textContent = "Silme işlemi başarıyla yapıldı!";
                    infoModal.style.display = "block";
                }

                else {
                    try {
                        if (DocumentId === 0) {
                            res = await axios.post('http://localhost:8000/api/documents/CreateDocument', {
                                Name: documentName,
                                FilePath: filePath,
                                Status: status,
                                UserId: userId
                            });
                        } else {
                            const document = await axios.get("http://localhost:8000/api/documents/GetDocument/" + DocumentId);

                            res = await axios.put("http://localhost:8000/api/documents/EditDocument", {
                                Id: DocumentId,
                                Name: documentName,
                                FilePath: filePath,
                                Status: document.data.Status === 'Onay Bekliyor' ? 'OB' : (document.data.Status === 'Onaylandı' ? 'O' : 'R'),
                                UserId: userId
                            });
                        }

                        await SetInfoModalContent(res);
                        await UploadFile();
                        infoModal.style.display = "block";
                    }

                    catch(error)
                    {
                        if (error.response && error.response.status === 422) {
                            const validationErrors = error.response.data.errors;
                            displayValidationErrors(validationErrors, ['Name', 'FilePath']);
                        }
                    }
                }
            } catch (err) {
                console.error(err.message);
            }
        });

        const btnSaveAsClick = () => {
            DocumentId = 0;
        }

        const displayValidationErrors = (errors, fields) => {
            debugger;
            for (const field in errors) {
                const fieldError = document.querySelector(`#${field}-error`);

                if (fieldError) {
                    fieldError.style.display = "block";
                    fieldError.textContent = errors[field][0];
                }
            }

            const errorFields = Object.keys(errors);
            const correctFields = fields.filter(item => !errorFields.includes(item));

            for(let i = 0; i < correctFields.length; i++) {
                const fieldError = document.querySelector(`#${correctFields[i]}-error`);

                if (fieldError) {
                    fieldError.style.display = "none";
                }
            }
        }

        const usePagination = (data, pageIndex) => {
            let page = { val: pageIndex }
            const pageSize = 5;
            const totalItems = data.length;
            const totalPages = Math.ceil(totalItems / pageSize);

            const paginatedData = () => {
                return data.slice(
                    (page.val - 1) * pageSize,
                    page.val * pageSize
                );
            };

            const changePage = (newPage) => {
                if (newPage >= 1 && newPage <= totalPages) {
                    page.val = newPage;
                }
            };

            return {
                page,
                pageSize,
                totalItems,
                totalPages,
                paginatedData,
                changePage,
            };
        }

        const SetInfoMessageTitle = async (res) => {
            if (res.data.code === 204) {
                infoModalTitle.textContent = "Hata";
            } else {
                infoModalTitle.textContent = "Bilgi";
            }
        };

        const SetInfoModalContent = async (res) => {
            if (res.data.message != null) {
                infoModalTitle.textContent = "Hata";
                infoModalContent.textContent = res.data.message;
            } else {
                infoModalTitle.textContent = "Bilgi";
                infoModalContent.textContent = "Kayıt işlemi başarıyla yapıldı!";
            }
        };

        const btnPreviousDocumentClick = async () => {
            const allDocuments = await axios.get(`http://localhost:8000/api/documents/GetAllDocumentsByUserId/${ Cookies.get("userId") }`);
            const paginatedDocumentData = usePagination(allDocuments.data, documentPageIndex.value);
            documentPageIndex.value = (Number(documentPageIndex.value) - 1).toString();
            paginatedDocumentData.changePage(documentPageIndex.value);

            checkPreviousAndNextDocumentButtons(paginatedDocumentData);
            showDocumentData(paginatedDocumentData);
        }

        const btnNextDocumentClick = async () => {
            debugger;
            const allDocuments = await axios.get(`http://localhost:8000/api/documents/GetAllDocumentsByUserId/${ Cookies.get("userId") }`);
            const paginatedDocumentData = usePagination(allDocuments.data, documentPageIndex.value);
            documentPageIndex.value = (Number(documentPageIndex.value) + 1).toString();
            paginatedDocumentData.changePage(documentPageIndex.value);

            checkPreviousAndNextDocumentButtons(paginatedDocumentData);
            showDocumentData(paginatedDocumentData);
        }

        const checkPreviousAndNextDocumentButtons = (paginatedDocumentData) => {
            debugger;
            if(paginatedDocumentData.page.val <= 1) {
                btnPreviousDocument.classList.remove("btn-primary");
                btnPreviousDocument.classList.add("btn-dark");
                btnPreviousDocument.classList.add("disabled");
            }

            else {
                btnPreviousDocument.classList.remove("btn-dark");
                btnPreviousDocument.classList.add("btn-primary");
                btnPreviousDocument.classList.remove("disabled");
            }

            if(paginatedDocumentData.page.val >= paginatedDocumentData.totalPages) {
                btnNextDocument.classList.remove("btn-primary");
                btnNextDocument.classList.add("btn-dark");
                btnNextDocument.classList.add("disabled");
            }

            else {
                btnNextDocument.classList.remove("btn-dark");
                btnNextDocument.classList.add("btn-primary");
                btnNextDocument.classList.remove("disabled");
            }
        }

        const showDocumentData = (paginatedDocumentData) => {
            const tbody = document.querySelector('#recordListModal .modal-body table tbody');
            tbody.innerHTML = '';

            paginatedDocumentData.paginatedData().forEach(document => {
                tbody.innerHTML += `
                    <tr style="cursor: pointer" onclick="documentRecordListRowClick(${document.Id}, '${document.Name}', '${document.FilePath}',
                                                                                '${document.Status}', '${document.createdAt}', '${document.updatedAt}')">
                        <td>${document.Id}</td>
                        <td>${document.Name}</td>
                        <td class="columnStatus" style="${ document.Status === 'Onay Bekliyor' ? "color: orange" : (document.Status === 'Onaylandı' ? "color: green" : "color: red") }">${document.Status}</td>
                        <td>${document.createdAt}</td>
                        <td>${document.updatedAt}</td>
                    </tr>
                `;
            });

            documentPageCount.textContent = paginatedDocumentData.totalPages;
        }

        const pageIndexChanged = async (pageIndexInput, pageCountInput, btnPrevious, btnNext, url, method) => {
            debugger;
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get("userId");

            if(pageIndexInput.value !== '') {
                if(pageIndexInput.value <= 0) {
                    pageIndexInput.value = 1;
                }

                if(pageIndexInput.value > pageCountInput.innerHTML) {
                    pageIndexInput.value = pageCountInput.innerHTML;
                }

                const allData = await axios.get(`http://localhost:8000/api/${url}/${method}/${userId}`);
                const paginatedData = usePagination(allData.data, pageIndexInput.value);
                checkPreviousAndNextDocumentButtons(paginatedData);
                showDocumentData(paginatedData);
            }
        }

        const btnOkClick = () => {
            if (infoModal) {
                infoModal.style.display = "none";
            }

            window.location.reload();
        };

        const btnDeleteClick = () => {
            DocumentIsDeleted = true;
        };

        const btnRecordListClick = () => {
            if (recordListModal) {
                recordListModal.style.display = "block";
            }
        };

        const btnCloseClick = () => {
            debugger;
            recordListModal.style.display = "none";
        };

        const btnResetClick = () => {
            window.location.reload();
        };

        const documentRecordListRowClick = async (id, name, filePath, status, createdAt, updatedAt) => {
            DocumentId = id;
            DocumentName.value = name;
            FilePath.textContent = filePath;
            txtState.textContent = status;
            txtState.style.color = status === 'Onay Bekliyor' ? 'orange' : (status === 'Onaylandı' ? 'green' : 'red');
            txtCreatedDate.textContent = createdAt;
            txtUpdatedDate.textContent = updatedAt;
            recordListModal.style.display = "none";
        };

        const btnLoadDocumentClick = () => {
            if (divLoadDocument, divProfile) {
                divLoadDocument.style.display = "block";
                divProfile.style.display = "none";
            }
        };

        const handleFileChange = (event) => {
            debugger;
            const input = event.target;

            if (input.files && input.files.length > 0) {
                FilePath.style.display = "block";
                selectedFile = input.files[0];
                FilePath.textContent = "/storage/documents/" + selectedFile.name;
            } else {
                FilePath.style.display = "none";
                FilePath.textContent = "";
            }
        };

        const UploadFile = async () => {
            debugger;
            if (selectedFile) {
                const formData = new FormData();
                formData.append("file", selectedFile);
                formData.append("directoryName", 'documents');

                try {
                    const response = await fetch("http://localhost:3000/api/upload", {
                        method: "POST",
                        body: formData
                    });

                    const result = await response.json();
                    console.log("Upload result:", result);
                } catch (error) {
                    console.error("Upload error:", error);
                }
            }
        };

        const btnProfileClick = () => {
            divLoadDocument.style.display = "none";
            divProfile.style.display = "block";
        }

        const logout = () => {
            Cookies.remove("token");
            Cookies.remove("isAuthenticated");
            Cookies.remove("userId");
            window.location.pathname = "/login";
        };

        const createAndFillTable = async (tableId, apiUrl) => {
            try {
                debugger;
                const response = await axios.get(apiUrl);
                const data = response.data;

                const newTable = document.createElement('table');
                newTable.id = tableId + "Data";
                newTable.className = 'table table-striped';
                const tableHead = document.createElement('thead');

                tableHead.innerHTML = `
                    <tr>
                        <th>Id</th>
                        <th>Evrak Adı</th>
                        <th>Durumu</th>
                        <th>Oluşturulma Tarihi</th>
                        <th>Son Güncelleme Tarihi</th>
                    </tr>
                `;

                newTable.appendChild(tableHead);
                const tableBody = document.createElement('tbody');

                for (let i = 0; i < data.length; i++) {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${ data[i].Id }</td>
                        <td>${ data[i].Name }</td>
                        <td>${ data[i].Status}</td>
                        <td>${ data[i].createdAt }</td>
                        <td>${ data[i].updatedAt }</td>
                    `;

                    tableBody.appendChild(row);
                }

                newTable.appendChild(tableBody);
                const container = document.createElement('div');
                container.style.display = "none";

                if (container) {
                    container.appendChild(newTable);
                    document.body.appendChild(container);
                } else {
                    console.error('No container found to append the table.');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        const DownloadPdf = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const element = document.getElementById(tableId + 'Data');

            const options = {
                width: 1200,
                margin: 1,
                filename: tableId === 'documentTable' ? 'Documents.pdf' : '',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    windowWidth: document.body.scrollWidth,
                    windowHeight: document.body.scrollHeight
                },
                jsPDF: {
                    unit: 'mm',
                    format: ['297', '420'],
                    orientation: 'landscape'
                }
            };

            html2pdf().set(options).from(element).save();
        }

        const DownloadExcel = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const table = document.getElementById(tableId + 'Data');
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(table);

            const images = table.querySelectorAll('img');

            for (let i = 0; i < images.length; i++) {
                const img = images[i];
                const imageUrl = img.src;

                const cellAddress = `B${i + 2}`;

                ws[cellAddress] = {
                    t: 's',
                    v: 'Image Link',
                    l: { Target: imageUrl },
                };
            }

            XLSX.utils.book_append_sheet(wb, ws, 'Tablo');
            XLSX.writeFile(wb, tableId === 'documentTable' ? 'Documents.xlsx' : '');
        }

        const DownloadWord = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const table = document.getElementById(tableId + 'Data');

            const html = `
            <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40' lang="">
                <head>
                    <meta charset='utf-8'>
                    <title>${tableId === 'userTable' ? 'Kullanıcılar' : ''}</title>
                    <style>
                        @page {
                            size: 16.54in 11.69in;
                            margin: 20mm 20mm 20mm 20mm;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            table-layout: fixed;
                        }
                        th, td {
                            padding: 5px;
                            text-align: left;
                            border: 1px solid black;
                            word-wrap: break-word;
                        }
                        img {
                            max-width: 100px;
                            max-height: 100px;
                        }
                    </style>
                </head>
                <body>${table.outerHTML}</body>
            </html>`;

            const blob = new Blob(['\ufeff', html], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
            const objUrl = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = objUrl;
            link.download = tableId === 'documentTable' ? 'Documents.doc' : '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };
    </script>
@else
    <script type="text/javascript">
        let documentApprovePageIndex = document.getElementById('documentApprovePageIndex');
        let documentApprovePageCount = document.getElementById('documentApprovePageCount');
        let btnPreviousDocumentApprove = document.getElementById('btnPreviousDocumentApprove');
        let btnNextDocumentApprove = document.getElementById('btnNextDocumentApprove');
        const infoModal = document.getElementById('infoModal');
        const infoModalTitle = document.querySelector('#infoModal .modal-title');
        const infoModalContent = document.querySelector('#infoModal .modal-body p');
        const lblUserName = document.getElementById("lblUserName");
        const lblEmail = document.getElementById("lblEmail");
        const lblMotherName = document.getElementById("lblMotherName");
        const lblFatherName = document.getElementById("lblFatherName");
        const lblRoleName = document.getElementById("lblRoleName");
        const lblBirthDate = document.getElementById("lblBirthDate");
        const lblGender = document.getElementById("lblGender");
        const lblCivilStatus = document.getElementById("lblCivilStatus");
        const lblEmploymentDate = document.getElementById("lblEmploymentDate");
        const lblCountryName = document.getElementById("lblCountryName");
        const lblMilitaryStatus = document.getElementById("lblMilitaryStatus");
        const lblPostponementDateText = document.getElementById("lblPostponementDateText");
        const lblCityName = document.getElementById("lblCityName");
        const lblDistrictName = document.getElementById("lblDistrictName");
        const lblAddress = document.getElementById("lblAddress");
        const profileImage = document.getElementById("profileImage");
        const profilePageImage = document.getElementById("profilePageImage");
        const divDocumentApprove = document.getElementById("divDocumentApprove");
        const btnDocumentApprove = document.getElementById("btnDocumentApprove");
        const headerDocumentApproveReject = document.getElementById("headerDocumentApproveReject");
        const columnStatus = document.getElementsByClassName("columnStatus");
        const lblHello = document.getElementById("lblHello");
        const lblProfileUserName = document.getElementById("lblProfileUserName");
        const lblProfileEmail = document.getElementById("lblProfileEmail");
        const lblProfileMotherName = document.getElementById("lblProfileMotherName");
        const lblProfileFatherName = document.getElementById("lblProfileFatherName");
        const lblProfileRole = document.getElementById("lblProfileRole");
        const lblProfileBirthDate = document.getElementById("lblProfileBirthDate");
        const lblProfileGender = document.getElementById("lblProfileGender");
        const lblProfileCivilStatus = document.getElementById("lblProfileCivilStatus");
        const lblProfileEmploymentDate = document.getElementById("lblProfileEmploymentDate");
        const lblProfileCountry = document.getElementById("lblProfileCountry");
        const lblProfileMilitaryStatus = document.getElementById("lblProfileMilitaryStatus");
        const lblProfilePostponementDate = document.getElementById("lblProfilePostponementDate");
        const lblProfileCity = document.getElementById("lblProfileCity");
        const lblProfileDistrict = document.getElementById("lblProfileDistrict");
        const lblProfileAddress = document.getElementById("lblProfileAddress");
        const btnOK = document.getElementById("btnOK");
        const page = document.getElementsByClassName("page");

        window.onload = async () => {
            const allDocuments = await axios.get("http://localhost:8000/api/documents/GetAllDocuments");
            const paginatedDocumentApproveData = usePagination(allDocuments.data, documentApprovePageIndex.value);
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get("userId");
            const userData = await axios.get("http://localhost:8000/api/users/GetUser/" + userId);
            profileImage.src = userData.data.ImagePath == null ? (userData.data.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png') : userData.data.ImagePath;
            profilePageImage.src = userData.data.ImagePath == null ? (userData.data.Gender === 'Erkek' ? '/storage/img/Man.png' : '/storage/img/Woman.png') : userData.data.ImagePath;
            checkPreviousAndNextDocumentApproveButtons(paginatedDocumentApproveData);
            showDocumentApproveData(paginatedDocumentApproveData);
            lblUserName.textContent = userData.data.UserName;
            lblEmail.textContent = userData.data.Email;
            lblMotherName.textContent = userData.data.MotherName;
            lblFatherName.textContent = userData.data.FatherName;
            lblRoleName.textContent = userData.data.Role.Name;
            lblBirthDate.textContent = userData.data.BirthDate;
            lblGender.textContent = userData.data.Gender;
            lblCivilStatus.textContent = userData.data.CivilStatus;
            lblEmploymentDate.textContent = userData.data.EmploymentDate;
            lblCountryName.textContent = userData.data.Country.Name;
            lblMilitaryStatus.textContent = userData.data.MilitaryStatus ?? '-';
            lblPostponementDateText.textContent = userData.data.PostponementDate ?? '-';
            lblCityName.textContent = userData.data.City.Name;
            lblDistrictName.textContent = userData.data.District.Name;
            lblAddress.textContent = userData.data.Address;
        }

        const pageIndexChanged = async (pageIndexInput, pageCountInput, btnPrevious, btnNext, url, method) => {
            debugger;

            if(pageIndexInput.value !== '') {
                if(pageIndexInput.value <= 0) {
                    pageIndexInput.value = 1;
                }

                if(pageIndexInput.value > pageCountInput.innerHTML) {
                    pageIndexInput.value = pageCountInput.innerHTML;
                }

                const allData = await axios.get(`http://localhost:8000/api/${url}/${method}`);
                const paginatedData = usePagination(allData.data, pageIndexInput.value);
                checkPreviousAndNextDocumentApproveButtons(paginatedData);
                showDocumentApproveData(paginatedData);
            }
        }

        const sortTable = (colIndex, tableId) => {
            debugger;
            const table = document.getElementById(tableId);
            const rows = Array.from(table.rows).slice(1);
            const isAsc = table.getAttribute('data-sort-asc') === 'true';

            rows.sort((a, b) => {
                const cellA = a.cells[colIndex].textContent.trim();
                const cellB = b.cells[colIndex].textContent.trim();
                return isAsc
                    ? cellA.localeCompare(cellB, undefined, { numeric: true })
                    : cellB.localeCompare(cellA, undefined, { numeric: true });
            });

            table.setAttribute('data-sort-asc', !isAsc);
            rows.forEach(row => table.tBodies[0].appendChild(row));
        }

        const filterTable = (colIndex, triggerElement, tableId) => {
            debugger;
            const modalId = `filter${tableId}`;
            let modalDiv = document.getElementById(modalId);

            if (!modalDiv) {
                modalDiv = document.createElement("div");
                modalDiv.id = `filter${tableId}`;
                modalDiv.style.zIndex = 2;
                modalDiv.classList = "filter-modal d-none";
                modalDiv.innerHTML = `<input type="text" style="border: 1px solid #02558B; border-radius: 5px;"
                                            id="filter${tableId}Input${colIndex}" placeholder="Değer girin">
                                      <button class="btn btn-primary" onclick="applyFilter(${colIndex}, this, '${tableId}', '${modalId}')">
                                            Ara
                                      </button>`;
                document.body.appendChild(modalDiv);
            }

            else {
                modalDiv.remove();
                modalDiv.innerHTML = `<input type="text" style="border: 1px solid #02558B; border-radius: 5px;"
                                            id="filter${tableId}Input${colIndex}" placeholder="Değer girin">
                                      <button class="btn btn-primary" onclick="applyFilter(${colIndex}, this, '${tableId}', '${modalId}')">
                                            Ara
                                      </button>`;
                document.body.appendChild(modalDiv);
            }

            const rect = triggerElement.getBoundingClientRect();
            modalDiv.style.top = `${rect.bottom + window.scrollY}px`;
            modalDiv.style.left = `${rect.left}px`;

            if(modalDiv.classList.contains("d-flex")) {
                modalDiv.classList.remove("d-flex");
                modalDiv.classList.add("d-none");
            }

            else {
                modalDiv.classList.remove("d-none");
                modalDiv.classList.add("d-flex");
            }
        }

        function applyFilter(colIndex, buttonElement, tableId, modalId) {
            debugger;
            const input = buttonElement.previousElementSibling.value.toLowerCase();
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll("tbody tr");
            const filterModal = document.getElementById(modalId);

            rows.forEach(row => {
                const col = row.cells[colIndex];

                if (col) {
                    const colText = col.textContent.toLowerCase();
                    row.style.display = colText.includes(input) ? "" : "none";
                    filterModal.classList.remove("d-flex");
                    filterModal.classList.add("d-none");
                }
            });
        }

        const usePagination = (data, pageIndex) => {
            let page = { val: pageIndex }
            const pageSize = 5;
            const totalItems = data.length;
            const totalPages = Math.ceil(totalItems / pageSize);

            const paginatedData = () => {
                return data.slice(
                    (page.val - 1) * pageSize,
                    page.val * pageSize
                );
            };

            const changePage = (newPage) => {
                if (newPage >= 1 && newPage <= totalPages) {
                    page.val = newPage;
                }
            };

            return {
                page,
                pageSize,
                totalItems,
                totalPages,
                paginatedData,
                changePage,
            };
        }

        const checkPreviousAndNextDocumentApproveButtons = (paginatedDocumentApproveData) => {
            debugger;
            if(paginatedDocumentApproveData.page.val <= 1) {
                btnPreviousDocumentApprove.classList.remove("btn-primary");
                btnPreviousDocumentApprove.classList.add("btn-dark");
                btnPreviousDocumentApprove.classList.add("disabled");
            }

            else {
                btnPreviousDocumentApprove.classList.remove("btn-dark");
                btnPreviousDocumentApprove.classList.add("btn-primary");
                btnPreviousDocumentApprove.classList.remove("disabled");
            }

            if(paginatedDocumentApproveData.page.val >= paginatedDocumentApproveData.totalPages) {
                btnNextDocumentApprove.classList.remove("btn-primary");
                btnNextDocumentApprove.classList.add("btn-dark");
                btnNextDocumentApprove.classList.add("disabled");
            }

            else {
                btnNextDocumentApprove.classList.remove("btn-dark");
                btnNextDocumentApprove.classList.add("btn-primary");
                btnNextDocumentApprove.classList.remove("disabled");
            }
        }

        const showDocumentApproveData = (paginatedDocumentApproveData) => {
            const tbody = document.querySelector('#divDocumentApprove table tbody');
            tbody.innerHTML = '';

            paginatedDocumentApproveData.paginatedData().forEach(document => {
                tbody.innerHTML += `
                    <tr>
                        <td>${document.Id}</td>
                        <td>${document.Name}</td>
                        <td>${document.User.FirstName + ' ' + document.User.LastName}</td>
                        <td>${document.createdAt}</td>
                        <td>${document.updatedAt}</td>
                        <td>
                            <a href="${document.FilePath}" download="${getFileNameFromPath(document.FilePath)}" class="btn btn-danger">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </td>
                        <td class="columnStatus" style="${document.Status === 'Onay Bekliyor' ? 'color: orange' : (document.Status === 'Onaylandı' ? 'color: green' : 'color: red')}">${document.Status}</td>
                        <td>
                            <a class="btn p-1 border-0 ${ document.Status === 'Onaylandı' || document.Status === 'Reddedildi' ? 'disabled' : '' }"
                                                                                       onclick="btnApproveRejectClick(${document.Id},
                                                                                                                      '${document.Name}',
                                                                                                                      '${document.createdAt}',
                                                                                                                      '${document.User.FirstName}',
                                                                                                                      '${document.User.LastName}',
                                                                                                                      '${document.User.Email}', true)">

                                <i class="fa-solid fa-circle-check fa-2x" style="color: green;"></i>
                            </a>

                            <a class="btn p-1 border-0 ${ document.Status === 'Onaylandı' || document.Status === 'Reddedildi' ? 'disabled' : '' }"
                                                                                        onclick="btnApproveRejectClick(${document.Id},
                                                                                                                       '${document.Name}',
                                                                                                                       '${document.createdAt}',
                                                                                                                       '${document.User.FirstName}',
                                                                                                                       '${document.User.LastName}',
                                                                                                                       '${document.User.Email}', false)">

                                <i class="fa-solid fa-circle-xmark fa-2x" style="width: 30px; height: 30px; color: red;"></i>
                            </a>

                            <a class="btn btn-primary p-1 m-1" style="${ document.Status === 'Onaylandı' || document.Status === 'Reddedildi' ? 'display: inline-block' : 'display: none' }"
                                                                                      onclick="btnUndoClick(${document.Id},
                                                                                                            '${document.Name}',
                                                                                                            ${document.Status === 'Onaylandı'})">

                                <i class="fa-solid fa-rotate-left" style="width: 20px; height: 20px"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });

            documentApprovePageCount.textContent = paginatedDocumentApproveData.totalPages;
        }

        const btnPreviousDocumentApproveClick = async () => {
            const allDocuments = await axios.get(`http://localhost:8000/api/documents/GetAllDocuments`);
            const paginatedDocumentApproveData = usePagination(allDocuments.data, documentApprovePageIndex.value);
            documentApprovePageIndex.value = (Number(documentApprovePageIndex.value) - 1).toString();
            paginatedDocumentApproveData.changePage(documentApprovePageIndex.value);

            checkPreviousAndNextDocumentApproveButtons(paginatedDocumentApproveData);
            showDocumentApproveData(paginatedDocumentApproveData);
        }

        const btnNextDocumentApproveClick = async () => {
            const allDocuments = await axios.get(`http://localhost:8000/api/documents/GetAllDocuments`);
            const paginatedDocumentApproveData = usePagination(allDocuments.data, documentApprovePageIndex.value);
            documentApprovePageIndex.value = (Number(documentApprovePageIndex.value) + 1).toString();
            paginatedDocumentApproveData.changePage(documentApprovePageIndex.value);

            checkPreviousAndNextDocumentApproveButtons(paginatedDocumentApproveData);
            showDocumentApproveData(paginatedDocumentApproveData);
        }

        const btnApproveRejectClick = async (id, name, createDate, toUserFirstName, toUserLastName, toUserEmail, isApprove) => {
            debugger;
            const document = await axios.get("http://localhost:8000/api/documents/GetDocument/" + id);

            const updated = await axios.put(
                `http://localhost:8000/api/documents/EditDocument`,
                {
                    Id: id,
                    Name: name,
                    FilePath: document.data.FilePath,
                    Status: isApprove ? "O" : "R",
                    UserId: document.data.UserId
                }
            );

            const user = await axios.get(
                "http://localhost:8000/api/users/GetUser/" + Cookies.get("userId")
            );

            if (updated) {
                await SendNotification(isApprove);

                await SendEmail(
                    name,
                    createDate,
                    toUserFirstName,
                    toUserLastName,
                    toUserEmail,
                    user.data.Email,
                    user.data.FirstName,
                    user.data.LastName,
                    isApprove ? "Approve" : "Reject"
                );

                window.location.reload();
            } else {
                infoModalTitle.value = "Hata";
                infoModalContent.value = `Evrak ${
                    isApprove ? "onay" : "red"
                } işlemi sırasında bir hata oluştu`;

                infoModal.style.display = "block";
            }
        };

        const SendNotification = async (isApprove) => {
            debugger;
            if ("serviceWorker" in navigator) {
                try {
                    const registration = await navigator.serviceWorker.register(
                        "/service-worker.js"
                    );

                    const permission = await Notification.requestPermission();

                    if (permission !== "granted") {
                        console.log("Bildirim izni verilmedi");
                        return;
                    }

                    const res = await axios.get(
                        "http://localhost:8000/api/users/GetVapidKeys"
                    );

                    const subscribe = await registration.pushManager.getSubscription();

                    if (subscribe) {
                        await subscribe.unsubscribe();
                    }

                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(res.data.publicKey),
                    });

                    await axios.post(
                        "http://localhost:8000/api/users/SendNotification",
                        {
                            Subscription: JSON.stringify(subscription),
                            PublicKey: res.data.publicKey,
                            PrivateKey: res.data.privateKey,
                            IsApprove: isApprove,
                        },
                        {
                            headers: {
                                Authorization: `Bearer ${Cookies.get("token")}`,
                            },
                        }
                    );
                } catch (error) {
                    console.error("Service Worker kaydı veya abonelik hatası:", error);
                }
            }
        };

        const SendEmail = async (documentName, createDate, toUserFirstName, toUserLastName, toUserEmail, fromUserEmail,
                                 fromUserFirstName, fromUserLastName, mailType) => {
            debugger;
            await axios.post("http://localhost:8000/api/users/SendEmail", {
                DocumentName: documentName,
                CreateDate: createDate,
                ToUserFirstName: toUserFirstName,
                ToUserLastName: toUserLastName,
                ToUserEmail: toUserEmail,
                FromUserEmail: fromUserEmail,
                FromUserFirstName: fromUserFirstName,
                FromUserLastName: fromUserLastName,
                MailType: mailType,
            });
        };

        const urlBase64ToUint8Array = (base64String) => {
            const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, "+")
                .replace(/_/g, "/");
            const rawData = atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }

            return outputArray;
        };

        const btnUndoClick = async (id, name, isApprove) => {
            const document = await axios.get("http://localhost:8000/api/documents/GetDocument/" + id);

            const updated = await axios.put(
                `http://localhost:8000/api/documents/EditDocument`,
                {
                    Id: id,
                    Name: name,
                    FilePath: document.data.FilePath,
                    Status: "OB",
                    UserId: document.data.UserId,
                }
            );

            if (updated) {
                infoModalTitle.textContent = "Bilgi";
                infoModalContent.textContent = isApprove
                    ? "Onaylanan evrak geri alındı."
                    : "Reddedilen evrak geri alındı.";

                infoModal.style.display = "block";
            }
        };

        const btnOkClick = () => {
            if (infoModal) {
                infoModal.style.display = "none";
            }

            window.location.reload();
        };

        const getFileNameFromPath = (filePath) => {
            const fileParts = filePath.split("/");
            return fileParts[fileParts.length - 1];
        };

        const btnProfileClick = () => {
            divDocumentApprove.style.display = "none";
            divProfile.style.display = "block";
        }

        const btnDocumentApproveClick = () => {
            divDocumentApprove.style.display = "block";
            divProfile.style.display = "none";
        }

        const logout = () => {
            Cookies.remove("token");
            Cookies.remove("isAuthenticated");
            Cookies.remove("userId");
            window.location.pathname = "/login";
        };

        const createAndFillTable = async (tableId, apiUrl) => {
            try {
                debugger;
                const response = await axios.get(apiUrl);
                const data = response.data;

                const newTable = document.createElement('table');
                newTable.id = tableId + "Data";
                newTable.className = 'table table-striped';
                const tableHead = document.createElement('thead');

                tableHead.innerHTML = `
                    <tr>
                        <th>Id</th>
                        <th>Evrak Adı</th>
                        <th>Kullanıcı</th>
                        <th>Oluşturulma Tarihi</th>
                        <th>Son Güncelleme Tarihi</th>
                        <th>Durumu</th>
                    </tr>
                `;

                newTable.appendChild(tableHead);
                const tableBody = document.createElement('tbody');

                for (let i = 0; i < data.length; i++) {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${ data[i].Id }</td>
                        <td>${ data[i].Name }</td>
                        <td>${ data[i].User.FirstName + ' ' + data[i].User.LastName }</td>
                        <td>${ data[i].createdAt }</td>
                        <td>${ data[i].updatedAt }</td>
                        <td>${ data[i].Status }</td>
                    `;

                    tableBody.appendChild(row);
                }

                newTable.appendChild(tableBody);
                const container = document.createElement('div');
                container.style.display = "none";

                if (container) {
                    container.appendChild(newTable);
                    document.body.appendChild(container);
                } else {
                    console.error('No container found to append the table.');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        const DownloadPdf = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const element = document.getElementById(tableId + 'Data');

            const options = {
                width: 1200,
                margin: 1,
                filename: tableId === 'documentApproveTable' ? 'DocumentApproves.pdf' : '',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    windowWidth: document.body.scrollWidth,
                    windowHeight: document.body.scrollHeight
                },
                jsPDF: {
                    unit: 'mm',
                    format: ['297', '420'],
                    orientation: 'landscape'
                }
            };

            html2pdf().set(options).from(element).save();
        }

        const DownloadExcel = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const table = document.getElementById(tableId + 'Data');
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(table);

            const images = table.querySelectorAll('img');

            for (let i = 0; i < images.length; i++) {
                const img = images[i];
                const imageUrl = img.src;

                const cellAddress = `B${i + 2}`;

                ws[cellAddress] = {
                    t: 's',
                    v: 'Image Link',
                    l: { Target: imageUrl },
                };
            }

            XLSX.utils.book_append_sheet(wb, ws, 'Tablo');
            XLSX.writeFile(wb, tableId === 'documentApproveTable' ? 'DocumentApproves.xlsx' : '');
        }

        const DownloadWord = async (tableId, url, method) => {
            debugger;
            await createAndFillTable(tableId, `http://localhost:8000/api/${url}/${method}`);
            const table = document.getElementById(tableId + 'Data');

            const html = `
            <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40' lang="">
                <head>
                    <meta charset='utf-8'>
                    <title>${tableId === 'documentApproveTable' ? 'Evrak Talepleri' : ''}</title>
                    <style>
                        @page {
                            size: 16.54in 11.69in;
                            margin: 20mm 20mm 20mm 20mm;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            table-layout: fixed;
                        }
                        th, td {
                            padding: 5px;
                            text-align: left;
                            border: 1px solid black;
                            word-wrap: break-word;
                        }
                        img {
                            max-width: 100px;
                            max-height: 100px;
                        }
                    </style>
                </head>
                <body>${table.outerHTML}</body>
            </html>`;

            const blob = new Blob(['\ufeff', html], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
            const objUrl = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = objUrl;
            link.download = tableId === 'documentApproveTable' ? 'DocumentApproves.doc' : '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };
    </script>
@endif
