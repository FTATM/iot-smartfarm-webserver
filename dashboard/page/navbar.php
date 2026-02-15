<?php
// กำหนดหน้าที่กำลังเปิดอยู่
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Hamburger Button - แสดงเฉพาะปุ่ม 3 ขีด */
    /* Hamburger Button - Responsive จนถึงทีวี 75" */
    .hamburger-btn {
        width: 40px;
        height: 40px;
        background: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
        z-index: 100;
        transition: all 0.3s ease;
    }

    .hamburger-btn span {
        width: 20px;
        height: 3px;
        background: #ff8021;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    /* Tablet (640px+) */
    @media screen and (min-width: 640px) {
        .hamburger-btn {
            width: 44px;
            height: 44px;
            gap: 6px;
        }

        .hamburger-btn span {
            width: 24px;
            height: 3px;
        }
    }

    /* Desktop (1024px+) */
    @media screen and (min-width: 1024px) {
        .hamburger-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
        }

        .hamburger-btn span {
            width: 26px;
        }
    }

    /* Large Desktop (1536px+) */
    @media screen and (min-width: 1536px) {
        .hamburger-btn {
            width: 56px;
            height: 56px;
            gap: 7px;
        }

        .hamburger-btn span {
            width: 30px;
            height: 4px;
        }
    }

    /* Full HD (1920px+) */
    @media screen and (min-width: 1920px) {
        .hamburger-btn {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            gap: 8px;
        }

        .hamburger-btn span {
            width: 36px;
            height: 4px;
        }
    }

    /* 4K / TV 75" (3840px+) */
    @media screen and (min-width: 3840px) {
        .hamburger-btn {
            width: 80px;
            height: 80px;
            border-radius: 18px;
            gap: 10px;
        }

        .hamburger-btn span {
            width: 48px;
            height: 6px;
        }
    }

    .hamburger-btn:hover {
        background: #f0f4ff;
        box-shadow: 0 6px 20px #DA7F3A88;
    }

    .hamburger-btn.hide {
        opacity: 0;
        pointer-events: none;
    }

    /* 🌑 Sidebar Overlay */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(216, 216, 216, 0.25);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 998;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* 📱 Sidebar Menu - จนถึงทีวี 75" */
    .sidebar-menu {
        position: fixed;
        left: -280px;
        top: 0;
        width: 280px;
        height: 100vh;
        background: white;
        box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
        transition: left 0.3s ease;
        z-index: 999;
        overflow-y: auto;
    }

    .sidebar-menu.active {
        left: 0;
    }

    /* Tablet (640px+) */
    @media screen and (min-width: 640px) {
        .sidebar-menu {
            left: -320px;
            width: 320px;
        }
    }

    /* Desktop (1024px+) */
    @media screen and (min-width: 1024px) {
        .sidebar-menu {
            left: -380px;
            width: 380px;
        }
    }

    /* Large Desktop (1536px+) */
    @media screen and (min-width: 1536px) {
        .sidebar-menu {
            left: -440px;
            width: 440px;
        }
    }

    /* Full HD (1920px+) */
    @media screen and (min-width: 1920px) {
        .sidebar-menu {
            left: -520px;
            width: 520px;
        }
    }

    /* 2K (2560px+) */
    @media screen and (min-width: 2560px) {
        .sidebar-menu {
            left: -640px;
            width: 640px;
        }
    }

    /* 4K / TV 75" (3840px+) */
    @media screen and (min-width: 3840px) {
        .sidebar-menu {
            left: -800px;
            width: 800px;
        }
    }

    /* ✕ ปุ่มปิด - จนถึงทีวี 75" */
    .close-btn {
        width: 40px;
        height: 40px;
        background: #FFF5EE;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        color: #ff8021;
        transition: all 0.3s ease;
    }

    /* Tablet (640px+) */
    @media screen and (min-width: 640px) {
        .close-btn {
            width: 44px;
            height: 44px;
            font-size: 1.75rem;
        }
    }

    /* Desktop (1024px+) */
    @media screen and (min-width: 1024px) {
        .close-btn {
            width: 50px;
            height: 50px;
            font-size: 2rem;
        }
    }

    /* Large Desktop (1536px+) */
    @media screen and (min-width: 1536px) {
        .close-btn {
            width: 56px;
            height: 56px;
            font-size: 2.25rem;
        }
    }

    /* Full HD (1920px+) */
    @media screen and (min-width: 1920px) {
        .close-btn {
            width: 64px;
            height: 64px;
            font-size: 2.5rem;
        }
    }

    /* 2K (2560px+) */
    @media screen and (min-width: 2560px) {
        .close-btn {
            width: 72px;
            height: 72px;
            font-size: 3rem;
        }
    }

    /* 4K / TV 75" (3840px+) */
    @media screen and (min-width: 3840px) {
        .close-btn {
            width: 100px;
            height: 100px;
            font-size: 3.5rem;
        }
    }

    .close-btn:hover {
        background: #ff8021;
        color: white;
        transform: rotate(90deg);
    }

    /* 📋 Navigation Menu - จนถึงทีวี 75" */
    .nav-list {
        list-style: none;
        padding: 1rem 0;
    }

    .nav-list li {
        border-bottom: 1px solid #f0f0f0;
    }

    .nav-list li:last-child {
        border-bottom: none;
    }

    .nav-list li a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 1rem 1.5rem;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        position: relative;
    }

    /* Tablet (640px+) */
    @media screen and (min-width: 640px) {
        .nav-list li a {
            padding: 1.1rem 1.75rem;
            font-size: 1rem;
            gap: 16px;
        }
    }

    /* Desktop (1024px+) */
    @media screen and (min-width: 1024px) {
        .nav-list li a {
            padding: 1.3rem 2rem;
            font-size: 1.1rem;
            gap: 18px;
        }
    }

    /* Large Desktop (1536px+) */
    @media screen and (min-width: 1536px) {
        .nav-list li a {
            padding: 1.5rem 2.25rem;
            font-size: 1.2rem;
            gap: 20px;
        }
    }

    /* Full HD (1920px+) */
    @media screen and (min-width: 1920px) {
        .nav-list li a {
            padding: 1.75rem 2.5rem;
            font-size: 1.35rem;
            gap: 22px;
        }
    }

    /* 2K (2560px+) */
    @media screen and (min-width: 2560px) {
        .nav-list li a {
            padding: 2rem 3rem;
            font-size: 1.6rem;
            gap: 26px;
        }
    }

    /* 4K / TV 75" (3840px+) */
    @media screen and (min-width: 3840px) {
        .nav-list li a {
            padding: 2.5rem 3.5rem;
            font-size: 2rem;
            gap: 32px;
        }
    }

    .nav-list li a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: #ff8021;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    @media screen and (min-width: 1920px) {
        .nav-list li a::before {
            width: 6px;
        }
    }

    @media screen and (min-width: 3840px) {
        .nav-list li a::before {
            width: 8px;
        }
    }

    .nav-list li a:hover {
        background: #FFFFFF;
        color: #ff8021;
        padding-left: 2rem;
    }

    @media screen and (min-width: 1024px) {
        .nav-list li a:hover {
            padding-left: 2.5rem;
        }
    }

    @media screen and (min-width: 1920px) {
        .nav-list li a:hover {
            padding-left: 3rem;
        }
    }

    @media screen and (min-width: 3840px) {
        .nav-list li a:hover {
            padding-left: 4rem;
        }
    }

    .nav-list li a:hover::before {
        transform: scaleY(1);
    }

    .nav-list li a.active {
        background: #FFFFFF;
        color: #ff8021;
        font-weight: 600;
    }

    .nav-list li a.active::before {
        transform: scaleY(1);
    }

    /* 🎨 Menu Icons - จนถึงทีวี 75" */
    .nav-icon {
        font-size: 1.3rem;
        width: 24px;
        text-align: center;
    }

    @media screen and (min-width: 640px) {
        .nav-icon {
            width: 26px;
        }
    }

    @media screen and (min-width: 1024px) {
        .nav-icon {
            width: 30px;
        }
    }

    @media screen and (min-width: 1536px) {
        .nav-icon {
            width: 34px;
        }
    }

    @media screen and (min-width: 1920px) {
        .nav-icon {
            width: 38px;
        }
    }

    @media screen and (min-width: 2560px) {
        .nav-icon {
            width: 46px;
        }
    }

    @media screen and (min-width: 3840px) {
        .nav-icon {
            width: 56px;
        }
    }

    /* Icon Classes - จนถึงทีวี 75" */
    .emojione-monotone--shrimp,
    .emojione-monotone--chicken,
    .fluent--door-arrow-right-28-regular,
    .fluent--door-arrow-left-20-regular,
    .arcticons--weathercan,
    .ph--solar-panel-duotone {
        display: inline-block;
        width: 24px;
        height: 24px;
        background-color: currentColor;
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
        -webkit-mask-size: 100% 100%;
        mask-size: 100% 100%;
    }

    /* Tablet (640px+) */
    @media screen and (min-width: 640px) {

        .emojione-monotone--shrimp,
        .emojione-monotone--chicken,
        .fluent--door-arrow-right-28-regular,
        .fluent--door-arrow-left-20-regular,
        .arcticons--weathercan,
        .ph--solar-panel-duotone {
            width: 26px;
            height: 26px;
        }
    }

    /* Desktop (1024px+) */
    @media screen and (min-width: 1024px) {

        .emojione-monotone--shrimp,
        .emojione-monotone--chicken,
        .fluent--door-arrow-right-28-regular,
        .fluent--door-arrow-left-20-regular,
        .arcticons--weathercan,
        .ph--solar-panel-duotone {
            width: 30px;
            height: 30px;
        }
    }

    /* Large Desktop (1536px+) */
    @media screen and (min-width: 1536px) {

        .emojione-monotone--shrimp,
        .emojione-monotone--chicken,
        .fluent--door-arrow-right-28-regular,
        .fluent--door-arrow-left-20-regular,
        .arcticons--weathercan,
        .ph--solar-panel-duotone {
            width: 34px;
            height: 34px;
        }
    }

    /* Full HD (1920px+) */
    @media screen and (min-width: 1920px) {

        .emojione-monotone--shrimp,
        .emojione-monotone--chicken,
        .fluent--door-arrow-right-28-regular,
        .fluent--door-arrow-left-20-regular,
        .arcticons--weathercan,
        .ph--solar-panel-duotone {
            width: 38px;
            height: 38px;
        }
    }

    /* 2K (2560px+) */
    @media screen and (min-width: 2560px) {

        .emojione-monotone--shrimp,
        .emojione-monotone--chicken,
        .fluent--door-arrow-right-28-regular,
        .fluent--door-arrow-left-20-regular,
        .arcticons--weathercan,
        .ph--solar-panel-duotone {
            width: 46px;
            height: 46px;
        }
    }

    /* 4K / TV 75" (3840px+) */
    @media screen and (min-width: 3840px) {

        .emojione-monotone--shrimp,
        .emojione-monotone--chicken,
        .fluent--door-arrow-right-28-regular,
        .fluent--door-arrow-left-20-regular,
        .arcticons--weathercan,
        .ph--solar-panel-duotone {
            width: 56px;
            height: 56px;
        }
    }

    /* เก็บ SVG URLs เดิมทั้งหมด */
    .emojione-monotone--shrimp {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath fill='%23000' d='M36.532 24.117c8.79-.436 18.323-7.866 18.323-7.866l-5.197-1.033L60 8.633s-12.077 2.253-17.963 1.715a5.4 5.4 0 0 0-2.443-1.73a5.4 5.4 0 0 0-1.763-.295a5.43 5.43 0 0 0-4.706 2.743c-1.683-.604-3.727-.818-5.849-.907c-1.65-.058-3.372-.049-5.068-.162c-.419-.04-.842-.066-1.265-.12l-1.266-.217a47 47 0 0 1-2.493-.529c-1.619-.409-3.133-.958-4.437-1.645a91 91 0 0 1-.931-.539c-.302-.187-.56-.409-.832-.598c-.534-.38-1.006-.809-1.423-1.189c-.832-.773-1.399-1.552-1.498-2.191c-.124-.649.19-.972.153-.969c.04 0-.305.296-.221.978c.058.675.591 1.52 1.383 2.362c.404.418.842.868 1.367 1.298c.27.217.52.466.818.67l.928.628c1.301.795 2.833 1.462 4.47 1.986c.82.258 1.659.484 2.513.702l1.283.301c.438.083.884.141 1.33.208c1.763.226 3.487.319 5.098.462c1.486.152 2.873.335 4.051.661a6 6 0 0 0-.721.777c-2.656.426-6.668 1.407-8.902 3.583q-.12.117-.231.245c-.251.06-.491.126-.731.191a17 17 0 0 0-2.292-.892c-.867-.291-1.755-.42-2.598-.592c-.86-.079-1.691-.218-2.491-.205c-.396-.008-.787-.012-1.163-.019l-1.103.068c-.711.02-1.368.148-1.972.219c-1.2.199-2.169.407-2.827.61c-.656.187-1.027.291-1.027.291s.371-.078 1.042-.224c.668-.157 1.646-.304 2.844-.426c2.379-.202 5.756-.176 9.038 1.063q.61.222 1.202.502c-2.011.669-3.561 1.522-4.62 2.554c-.8.777-1.237 1.925-1.271 3.263c-1.982 1.111-3.371 2.316-4.13 3.591c-.383.646-.537 1.417-.464 2.249c-1.438 1.335-2.958 3.1-3.481 4.941c-.242.862-.126 1.816.321 2.739c-1.081 1.807-1.641 3.419-1.656 4.803c-.014.733.234 1.462.713 2.12c-.162 1.869-.15 4.574.887 6.436c.302.55.77.999 1.367 1.322c.277 2.514 1.26 5.793 3.994 8.999L12.271 62l1.438-1.407c.563-.55 1.27-1.573 1.603-2.881a5.6 5.6 0 0 0 1.906.092l1.536-.197l.112-1.558c.013-.156.055-1.017-.179-2.275a5.2 5.2 0 0 0 1.899-.706l1.648-.997l-1.018-1.648c-.096-.152-2.141-3.41-5.798-5.127c-.098-.815-.327-1.676-.683-2.572c.551-1.116.882-2.482.992-4.08c.785-.76 1.454-1.77 2.016-2.984c.99 1.322 2.431 2.726 4.449 3.594c.251.105.578.252.881-.16c.717.756 1.548 2.047 2.055 4.21c.226.972.158 1.591.89 1.559a.5.5 0 0 0 .188-.051c.286.637.63 1.647.751 3.07c0 0 .927-1.433.597-3.999c.073.007.133.002.218.02c1.13.216-.975-4.101-3.895-6.3c.347-.25.656-.171.416-.731c-.445-1.036-3.421-1.1-5.722-3.143q1.302-.833 2.474-2.282c.15 0 .309-.019.464-.028c1.253 1.327 3.226 2.888 5.922 3.442c.269.052.621.13.832-.337c.854.597 1.928 1.693 2.861 3.708c.419.907.475 1.526 1.185 1.35a.5.5 0 0 0 .173-.086c.408.565.948 1.487 1.354 2.855c0 0 .619-1.593-.221-4.04c.071-.008.13-.024.217-.025c1.15-.017-1.779-3.819-5.081-5.384c.285-.314.606-.299.26-.797c-.531-.763-2.618-.53-4.824-1.285c1.185-.453 2.396-1.147 3.624-2.094c1.478 1.039 3.649 2.13 6.313 2.168c.268.003.628.015.752-.483c.949.429 2.207 1.306 3.498 3.113c.579.811.748 1.406 1.414 1.104a.5.5 0 0 0 .154-.119c.508.48 1.206 1.286 1.859 2.553c0 0 .313-1.68-.965-3.927c.068-.021.121-.049.206-.065c1.127-.229-2.454-3.421-5.987-4.343c.221-.361.537-.405.104-.83c-.611-.603-2.369-.128-4.45-.31c1.779-.596 3.614-1.751 5.487-3.472c.111.001.223.008.336.002m-2.501-11.651a4.02 4.02 0 0 1 3.8-2.713c.433 0 .871.069 1.301.218a4.026 4.026 0 0 1 2.489 5.113a4.01 4.01 0 0 1-3.79 2.713a4.02 4.02 0 0 1-3.8-5.331M7.245 49.212c-.986-1.777-.714-4.938-.563-6.145q-.011-.01-.019-.02c.125.067 2.563 1.371 4.614.763c.599 1.501 1.222 4.569-2.583 6.262c-.662-.13-1.204-.421-1.449-.86m9.146.422c-.925.2-3.058-.215-3.058-.215c2.511 2.517 3.646 6.492 3.646 6.492c-3.438-1.345-4.648-3.632-4.648-3.632c.424 2.465.063 6.943.063 6.943c-2.817-3.309-3.563-6.669-3.631-9.134l-.041-.011c.409.021 4.634.164 5.054-3.021c1.655 1.132 2.615 2.578 2.615 2.578m6.362-27.196s1.016 2.182 3.125 3.234c-4.756 3.486-10.485-1.295-10.485-1.295c.876 2.398 1.982 3.813 3.186 4.567c-3.403 4.143-7.669.854-7.669.854c1.544 1.953 2.95 2.816 4.195 3.014c-.773 2.135-2.82 5.947-7.192 3.966c0 0 2.118 1.572 4.364 1.391c-.057 1.499-.688 5.216-5.62 4.873c-.478-.433-.777-.939-.768-1.423c.028-1.624 1.186-3.608 2.023-4.838c-.624-.73-.942-1.559-.757-2.211c.515-1.828 2.498-3.722 3.754-4.773c-.273-.741-.301-1.461-.004-1.959c.892-1.501 2.998-2.731 4.486-3.458c-.219-1.24-.035-2.385.604-3.004c1.358-1.321 3.687-2.136 5.674-2.621q.565.355 1.119.733c.76.514 1.519 1.028 2.265 1.53c.757.501 1.557.937 2.313 1.389c.623.361 1.283.735 1.999 1.025c-1.94.722-4.341.877-6.612-.994m9.709-1.481c-.228 1.145-1.213 1.457-2.27 1.12c-.674-.194-1.387-.549-2.17-.952c-.742-.396-1.521-.761-2.287-1.206c-.771-.461-1.529-.934-2.336-1.397c-.204-.122-.416-.238-.623-.357c.05-.061.095-.123.15-.177c2.207-2.153 7.004-2.963 8.685-3.188c.236-.408.506-.737.803-1.018c0 .813.163 1.623.526 2.375a5.4 5.4 0 0 0 3.122 2.773c.57.197 1.167.298 1.769.298a5.43 5.43 0 0 0 5.129-3.676a5.4 5.4 0 0 0 .063-3.319c3.577.562 11.115-1.385 11.115-1.385l-8.622 5.019l5.332 1.234s-14.39 9.581-19.846-.946c-.542 1.576-.146 3.524 1.46 4.802'/%3E%3Cpath fill='%23000' d='M37.202 15.6a1.923 1.923 0 0 0 2.445-1.195a1.937 1.937 0 0 0-1.194-2.453a1.928 1.928 0 0 0-1.251 3.648'/%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
    }

    .emojione-monotone--chicken {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath fill='%23000' d='m62 29.167l-.327-1.213c-.09-.329-1.794-6.409-6.707-9.129c4.475-3.601 5.453-8.119 5.502-8.357l.269-1.319l-1.083-.753c-.291-.202-2.982-1.982-7.537-1.982c-3.678 0-7.488 1.16-11.326 3.448c-3.697 2.204-4.594 5.703-5.385 8.789c-1.006 3.932-1.824 7.056-7.806 8.482a33 33 0 0 1-1.463-.215l2.124-2s-4.656 1.5-6.412.003c-.1-.085-.211-.27-.203-.667c.016-.726.992-7.256.994-9.148c4.165-.976 6.219-5.271 4.447-6.351c-1.251-.764-2.824 1.219-5.736 1.859c.027-.383.035-.79-.006-1.242c-.227-2.526 3.335-3.503 2.903-4.805c-.561-1.689-5.144-.647-7.347 3.071a6 6 0 0 0-.61 1.465a7.4 7.4 0 0 0-.403-1.162c-1.025-2.31 2.176-4.398 1.197-5.522c-1.295-1.486-5.063 1.135-5.949 5.393a6.2 6.2 0 0 0 .07 2.696c-.088-.077-.161-.153-.257-.231c-1.628-1.298.027-4.048-1.194-4.556c-1.74-.727-3.314 2.85-2.366 6.347c.2.736.606 1.473 1.126 2.036c-3.282.371-5.704 3.53-5.811 3.674L2 18.712l1.141-.131c.013-.002.279-.03.689-.03c.573 0 1.359.068 2.132.3c-1.081 1.144-2.095 2.536-2.73 4.167c-1.465 3.758 1.86 1.811 2.537 1.893c0 0 .321.462.725.77c-.004.225-.021.436-.021.665c0 2.933.607 11.413.633 11.772l.182 2.524l1.508-1.931c2.675 7.763 8.889 14.146 16.18 15.771c.91 1.119 2.32 2.298 3.919 2.797c-2.45 3.485-6.984 3.83-6.984 4.721h13.886c0-.921-2.468-.701-4.164-4.698c1.734-.585 3.303-2.302 4.338-4.013c4.563-2.061 8.415-5.941 10.926-10.631c2.637.883 4.91 1.008 5.094 1.016l1.451.063l.467-1.419c.076-.23 1.578-4.879.965-9.325c3.662-.733 6.088-2.888 6.209-2.997zM6.749 18.071c-.88-.35-1.803-.483-2.534-.513c.958-.968 2.749-2.428 4.808-2.483q-.48.841-.884 1.815c-.446.344-.916.739-1.39 1.181m1.663 8.274c0-5.458 1.335-9.306 3.244-11.716c.068-.054.139-.104.207-.168c.548-.515 1.906-1.326 2.732-1.554c.616-.169 2.162-.101 3.132.587c.892.637 1.547 1.753 2.849 1.808c.041.002.079-.004.12-.003c-.037 1.815-.97 8.055-.988 8.91c-.019.841.243 1.57.756 2.11q.632.664 1.71.918l-2.159 1.482l1.771 2.147l-4.604-.493l.684 4.097l-4.182-3.104l-.576 4.729l-2.416-2.999l-1.824 2.336c-.188-2.816-.456-7.208-.456-9.087m26.516 25.228l-.297.129l-.164.286c-1.192 2.085-2.853 3.54-4.037 3.54c-1.321 0-3.026-1.054-4.147-2.561l-.224-.301l-.36-.073c-7.158-1.438-13.236-7.949-15.461-15.729l.477-.609l3.759 4.665l.727-5.965l5.38 3.996l-1.053-6.315l6.516.699c-2.584 1.981-3.816 4.976-3.247 8.088c.665 3.629 3.632 6.326 7.558 6.872c.908.126 1.807.189 2.674.189c6.772 0 9.977-3.809 10.109-3.972l.377-.459l-.217-.559c-.529-1.364-1.602-2.396-2.484-3.061c1.992-1.629 3.151-3.834 3.209-3.945l.496-.956l-.964-.433c-.964-.433-3.321-.742-5.056-.922a18 18 0 0 0 1.021-1.565l.653-1.149l-1.254-.324c-.07-.019-1.743-.444-4.081-.444c-2.904 0-5.485.635-7.67 1.888c-.326.188-.623.399-.918.614l-3.346-4.06l.881-.604c2.041.369 3.803.688 4.914.688c4.137 0 8.021-1.21 11.143-2.183c2.222-.691 4.141-1.29 5.55-1.29c1.007 0 2.692 0 2.692 4.811c-.001 8.887-5.534 17.724-13.156 21.014m1.541-15.569c1.456.11 3.724.349 5.166.626c-.642.919-1.707 2.201-3.074 2.952l-1.688.93l1.727.855c.02.01 1.748.879 2.613 2.302c-.987.898-3.628 2.815-8.186 2.815c-.781 0-1.593-.058-2.414-.172c-3.744-.521-5.508-3.059-5.912-5.262c-.486-2.659.819-5.234 3.409-6.72c1.894-1.085 4.157-1.636 6.728-1.636c.855 0 1.613.064 2.211.14c-.331.468-.744 1-1.195 1.466l-1.493 1.544zm15.993-4.77c1.603 4.387-.391 10.441-.391 10.441s-1.973-.09-4.296-.819c1.435-3.23 2.247-6.76 2.247-10.297c0-2.923-.48-6.811-4.632-6.811c-1.696 0-3.741.638-6.11 1.375c-1.877.585-3.904 1.211-6.048 1.626c5.401-4.245 2.722-11.694 8.528-15.157c4.104-2.446 7.617-3.179 10.355-3.179c4.094 0 6.453 1.641 6.453 1.641s-1.3 6.354-8.643 9.476c7.474.065 9.877 8.961 9.877 8.961s-3.035 2.743-7.34 2.743'/%3E%3Cellipse cx='6.905' cy='16.349' fill='%23000' rx='.65' ry='.326'/%3E%3Ccircle cx='12.335' cy='16.675' r='1.5' fill='%23000'/%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
    }

    .fluent--door-arrow-right-28-regular {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 28 28'%3E%3Cpath fill='%23000' d='M5 5a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v8.427a7.5 7.5 0 0 0-1.5-.36V5A1.5 1.5 0 0 0 20 3.5H8A1.5 1.5 0 0 0 6.5 5v18A1.5 1.5 0 0 0 8 24.5h6.155a7.5 7.5 0 0 0 1.246 1.5H8a3 3 0 0 1-3-3zm4.5 10a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3M27 20.5a6.5 6.5 0 1 1-13 0a6.5 6.5 0 0 1 13 0M16.5 20a.5.5 0 0 0 0 1h6.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 .146-.351v-.006a.5.5 0 0 0-.146-.35l-3-3a.5.5 0 0 0-.708.707L23.293 20z'/%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
    }

    .fluent--door-arrow-left-20-regular {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%23000' d='M6 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4.257a5.5 5.5 0 0 1-.657-1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v5.022q.516.047 1 .185V4a2 2 0 0 0-2-2zm2 8a1 1 0 1 1-2 0a1 1 0 0 1 2 0m11 4.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0m-6.853-.354l-.003.003a.5.5 0 0 0-.144.348v.006a.5.5 0 0 0 .146.35l2 2a.5.5 0 0 0 .708-.707L13.707 15H16.5a.5.5 0 0 0 0-1h-2.793l1.147-1.146a.5.5 0 0 0-.708-.708z'/%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
    }

    .arcticons--weathercan {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cpath fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' d='M22.51 15.44a6.94 6.94 0 0 0-12.42 4A6.23 6.23 0 0 0 8.38 31m9.19.8h10.74a4.87 4.87 0 0 0 4.87-4.87a4.8 4.8 0 0 0-.58-2.3' stroke-width='2'/%3E%3Ccircle cx='30.18' cy='17.11' r='6.02' fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'/%3E%3Cpath fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' d='M30.18 4.5v5.1m8.92-1.41l-3.61 3.61m7.3 5.31H37.7m1.4 8.92l-3.61-3.6M21.26 8.19l3.61 3.61m-8.75 21.51l-1.81-5.53l-4.24 4a3.83 3.83 0 0 0 1.2 6.39l.36.11l.38.08a3.83 3.83 0 0 0 4.11-5.05m5.5 6.11l-1.89-3.81L17.08 39a2.8 2.8 0 0 0 1.55 4.49h.55a2.8 2.8 0 0 0 2.44-4.07' stroke-width='2'/%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
    }

    .ph--solar-panel-duotone {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 256 256'%3E%3Cg fill='%23000'%3E%3Cpath d='M232 216H24l40.7-72h126.6Z' opacity='0.35'/%3E%3Cpath d='M32 104a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16H40a8 8 0 0 1-8-8m39.43-45.25a8 8 0 0 0 11.32-11.32L71.43 36.12a8 8 0 0 0-11.31 11.31ZM128 40a8 8 0 0 0 8-8V16a8 8 0 0 0-16 0v16a8 8 0 0 0 8 8m50.91 21.09a8 8 0 0 0 5.66-2.34l11.31-11.32a8 8 0 0 0-11.31-11.31l-11.32 11.31a8 8 0 0 0 5.66 13.66M192 104a8 8 0 0 0 8 8h16a8 8 0 0 0 0-16h-16a8 8 0 0 0-8 8m-104 8a8 8 0 0 0 8-8a32 32 0 0 1 64 0a8 8 0 0 0 16 0a48 48 0 0 0-96 0a8 8 0 0 0 8 8m150.91 108a8 8 0 0 1-6.91 4H24a8 8 0 0 1-7-11.94l40.69-72a8 8 0 0 1 7-4.06H191.3a8 8 0 0 1 7 4.06l40.69 72a8 8 0 0 1-.08 7.94m-52.27-68h-24.37l3.48 16h29.93Zm-37.26 16l-3.48-16h-35.8l-3.48 16Zm-46.24 16l-5.21 24h60.14l-5.21-24Zm-42.82-16h29.93l3.48-16H69.36Zm-22.61 40h43.84l5.22-24H51.28Zm180.58 0l-13.57-24h-35.49l5.22 24Z'/%3E%3C/g%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
    }

    /* ป้องกัน scroll เมื่อเมนูเปิด */
    body.menu-open {
        overflow: hidden;
    }
</style>

<!-- ปุ่ม Hamburger (3 ขีด) -->
<button class="hamburger-btn" id="hamburgerBtn">
    <span></span>
    <span></span>
    <span></span>
</button>

<!-- Overlay (พื้นหลังมืด) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar Menu -->
<nav class="sidebar-menu flex flex-col" id="sidebarMenu">

    <!-- 🔝 Header ของ Sidebar -->
    <div class="flex items-center justify-between p-3">
        <div>
            <img src="../../img/logo.png" alt="ฟิวด์เทค ออร์โตเมชั่น จำกัด" class="w-[5rem] object-cover rounded-md">
            <!-- <p class="text-lg font-bold">FieldTech Automation Co.,ltd</p> -->
        </div>
        <button
            id="closeBtn"
            title="ปิดเมนู"
            class="close-btn 
                   w-8 h-8 flex items-center justify-center
                   rounded-full bg-stone-100 text-stone-600
                   hover:bg-orange-100 hover:text-[#ff8021]
                   transition">
            ✕
        </button>
    </div>

    <ul class="nav-list flex-1">
        <li>
            <a href="shrimp-dashboard.php" class="<?php echo ($current_page == 'shrimp-dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon emojione-monotone--shrimp"></span>Shrimp Dashboard
            </a>
        </li>
        <li>
            <a href="chicken-dashboard.php" class="<?php echo ($current_page == 'chicken-dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon emojione-monotone--chicken"></span>Chicken Dashboard
            </a>
        </li>
        <li>
            <a href="outdoor-dashboard.php" class="<?php echo ($current_page == 'outdoor-dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon fluent--door-arrow-right-28-regular"></span>Outdoor Dashboard
            </a>
        </li>
        <li>
            <a href="indoor-dashboard.php" class="<?php echo ($current_page == 'indoor-dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon fluent--door-arrow-left-20-regular"></span>Indoor Dashboard
            </a>
        </li>
        <li>
            <a href="weather-dashboard.php" class="<?php echo ($current_page == 'weather-dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon arcticons--weathercan"></span>Weather Dashboard
            </a>
        </li>
        <li>
            <a href="solar-system-dashboard.php" class="<?php echo ($current_page == 'solar-system-dashboard') ? 'active' : ''; ?>">
                <span class="nav-icon ph--solar-panel-duotone"></span>Solar System Dashboard
            </a>
        </li>
    </ul>

</nav>


<script>
    // Get elements
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeBtn = document.getElementById('closeBtn');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // เปิดเมนู
    function openMenu() {
        sidebarMenu.classList.add('active');
        sidebarOverlay.classList.add('active');
        hamburgerBtn.classList.add('hide');
        document.body.classList.add('menu-open');
    }

    // ปิดเมนู
    function closeMenu() {
        sidebarMenu.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        hamburgerBtn.classList.remove('hide');
        document.body.classList.remove('menu-open');
    }

    // Event Listeners
    hamburgerBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    sidebarOverlay.addEventListener('click', closeMenu);

    // ปิดเมนูเมื่อกด ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebarMenu.classList.contains('active')) {
            closeMenu();
        }
    });

    // ปิดเมนูเมื่อคลิกลิงก์ (สำหรับ Single Page Application)
    const navLinks = document.querySelectorAll('.nav-list a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            closeMenu();
        });
    });

    // ป้องกันการปิดเมื่อคลิกใน sidebar
    sidebarMenu.addEventListener('click', (e) => {
        e.stopPropagation();
    });
</script>