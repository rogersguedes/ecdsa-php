<?php

require_once "src/ecdsaphp.php";


$success = 0;
$failure = 0;

function assertEqual($a, $b) {
    if ($a != $b) {
        $failure ++;
        echo "      FAIL: " . $a . " != " . $b;
    } else {
        $success ++;
        echo "      success";
    }
}

function printHeader($text) {
    echo "\n  " . $text . " test:";
}

function printSubHeader($text) {
    echo "    " . $text . ":";
}


echo "\n\nRunning ECDSA-Ruby tests:";

printHeader("ECDSA");

printSubHeader("testVerifyRightMessage");
$privateKey = new EcdsaPhp\PrivateKey;
$publicKey = $privateKey->publicKey();
$message = "This is the right message";
$signature = EcdsaPhp\Ecdsa::sign($message, $privateKey);
assertEqual(EcdsaPhp\Ecdsa::verify($message, $signature, $publicKey), true);

printSubHeader("testVerifyWrongMessage");
$privateKey = new EcdsaPhp\PrivateKey;
$publicKey = $privateKey->publicKey();
$message1 = "This is the right message";
$message2 = "This is the wrong message";
$signature = EcdsaPhp\Ecdsa::sign($message1, $privateKey);
assertEqual(EcdsaPhp\Ecdsa::verify($message2, $signature, $publicKey), false);


printHeader("openSSL");

printSubHeader("testAssign");
// Generated by: openssl ecparam -name secp256k1 -genkey -out privateKey.pem
$privateKeyPem = EcdsaPhp\Utils\File::read("test/privateKey.pem");
$privateKey = EcdsaPhp\PrivateKey::fromPem($privateKeyPem);
$message = EcdsaPhp\Utils\File::read("test/message.txt");
$signature = EcdsaPhp\Ecdsa::sign($message, $privateKey);
$publicKey = $privateKey->publicKey();
assertEqual(EcdsaPhp\Ecdsa::verify($message, $signature, $publicKey), true);

printSubHeader("testVerifySignature");
// openssl ec -in privateKey.pem -pubout -out publicKey.pem
$publicKeyPem = EcdsaPhp\Utils\File::read("test/publicKey.pem");
// openssl dgst -sha256 -sign privateKey.pem -out signature.binary message.txt
$signatureDer = EcdsaPhp\Utils\File::read("test/signatureDer.txt", "binary");
$message = EcdsaPhp\Utils\File::read("test/message.txt");
$publicKey = $PublicKey.fromPem($publicKeyPem);
$signature = $Signature.fromDer($signatureDer);
assertEqual(EcdsaPhp\Ecdsa::verify($message, $signature, $publicKey), true);


printHeader("PrivateKey");

printSubHeader("testPemConversion");
$privateKey1 = new EcdsaPhp\PrivateKey;
$pem = $privateKey1->toPem();
$privateKey2 = EcdsaPhp\PrivateKey::fromPem($pem);
assertEqual($privateKey1->toPem, $privateKey2->toPem);

printSubHeader("testDerConversion");
$privateKey1 = new EcdsaPhp\PrivateKey;
$der = $privateKey1->toDer();
$privateKey2 = EcdsaPhp\PrivateKey::fromDer($der);
assertEqual($privateKey1->toPem(), $privateKey2->toPem());

printSubHeader("testStringConversion");
$privateKey1 = new EcdsaPhp\PrivateKey;
$str = $privateKey1->toString();
$privateKey2 = EcdsaPhp\PrivateKey::fromString($str);
assertEqual($privateKey1->toPem(), $privateKey2->toPem());


printHeader("PublicKey");

printSubHeader("testPemConversion");
$privateKey = new EcdsaPhp\PrivateKey;
$publicKey1 = $privateKey->publicKey();
$pem = $publicKey1->toPem();
$publicKey2 = PublicKey::fromPem($pem);
assertEqual($publicKey1->toPem(), $publicKey2->toPem());

printSubHeader("testDerConversion");
$privateKey = new EcdsaPhp\PrivateKey;
$publicKey1 = $privateKey.publicKey();
$der = $publicKey1->toDer();
$publicKey2 = PublicKey::fromDer($der);
assertEqual($publicKey1->toPem(), $publicKey2->toPem());


printSubHeader("testStringConversion");
$privateKey = new EcdsaPhp\PrivateKey;
$publicKey1 = $privateKey->publicKey();
$str = $publicKey1->toString();
$publicKey2 = PublicKey::fromString($str);
assertEqual($publicKey1->toPem, $publicKey2->toPem);


printHeader("Signature");

printSubHeader("testDerConversion");
$privateKey = new EcdsaPhp\PrivateKey;
$message = "This is a text message";
$signature1 = EcdsaPhp\Ecdsa::sign($message, $privateKey);
$der = $signature1->toDer();
$signature2 = Signature::fromDer($der);
assertEqual($signature1->r, $signature2->r);
assertEqual($signature1->s, $signature2->s);

printSubHeader("testBase64Conversion");
$privateKey = new EcdsaPhp\PrivateKey;
$message = "This is a text message";
$signature1 = EcdsaPhp\Ecdsa::sign($message, $privateKey);
$base64 = $signature1->toBase64();
$signature2 = Signature::fromBase64($base64);
assertEqual($signature1->r, $signature2->r);
assertEqual($signature1->s, $signature2->s);


if ($failure == 0) {
    echo "\n\nALL " . $success . " TESTS SUCCESSFUL\n\n";
} else {
    echo "\n\n" . $failure . "/" . ($failure + $success) . " FAILURES OCCURRED\n\n";
}

?>