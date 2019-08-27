<?php include('config.php') ?>
<?php if (strpos($_SERVER["REQUEST_URI"], 'authenticate') !== false) { ?>
    <title>Signup and Play Fantasy Cricket To Win Cash Prize Daily | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Register on <?php echo SITE_NAME; ?> and Play Fantasy Cricket League. Create your virtual team, compete with others and win cash prize.">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'AboutUs') !== false) {
    ?>
    <title>About Us | Play Fantasy Cricket League Online and Win Cash Prizes | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo SITE_NAME; ?> is India`s fastest growing fantasy sports website, developed for sports fans, in particular for the cricket fans of India.Join <?php echo SITE_NAME; ?> and enjoy online cricket">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'contactUs') !== false) { ?>
    <title>Contact Us | India's No.1 Fantasy Cricket Platform</title>
    <meta name="description" content="Need any assistance? Contact us and get support from highly experienced support executives which are available 24*7 to help you">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'Faq') !== false) { ?>
    <title>FAQs- Frequently Asked Questions | <?php echo SITE_NAME; ?></title>
    <meta name="description" content="FAQs- <?php echo SITE_NAME; ?> is a daily fantasy sports platform where you can play fantasy cricket in this ICC Cricket World Cup and win exciting cash prize everyday.">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'Legalities') !== false) { ?>
    <title>Legalities- <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Legalities- <?php echo SITE_NAME; ?> offers services related to fantasy cricket leagues including fun features and contests and therefore it is completely legal.">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'PointSystem') !== false) { ?>
    <title>Point System for Fantasy Cricket Leagues- <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Want to know about our Point System for Fantasy Sports Leagues? Visit and learn how to earn points at <?php echo SITE_NAME; ?>">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'privacyPolicy') !== false) { ?>
    <title>Privacy Policy- <?php echo SITE_NAME; ?></title>
    <meta name="description" content="The Privacy Policy of <?php echo SITE_NAME; ?>  describes how we work to maintain that trust and protect that information.">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'TermConditions') !== FALSE) { ?>
    <title>Terms & Condition- <?php echo SITE_NAME; ?></title>
    <meta name="description" content="By accepting the Terms of Service on the registration page, you consent to provide sensitive personal data or personal information and are aware of the purpose of sharing such information.">
<?php } elseif (strpos($_SERVER["REQUEST_URI"], 'download-app') !== FALSE) { ?>
    <title>Download Fantasy Cricket App | Daily Fantasy Cricket App</title>
    <meta name="description" content="Daily Fantasy Cricket App- Download the best Fantasy Cricket App in India, Play Fantasy Cricket and win cash prize daily in this ICC Cricket World Cup.">
<?php }else{
    echo "<title>".SITE_NAME."</title>";
}
?>