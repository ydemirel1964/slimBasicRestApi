<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//GET ALL COURSE LIST
$app->get('/courses', function (Request $request, Response $response, $args) {

    $db = new Db();
    try {

        $conn = $db->connect();
        $courses = $conn->query("SELECT * FROM coupons")->fetchAll();

        $response
            ->withStatus(200)
            ->withHeader("content-type", "application/json")
            ->getBody()
            ->write((string) json_encode([$courses]));
        $db = null;
        return $response;

    } catch (PDOException $e) {
        $response->getBody()->write((string) json_encode(['text' => $e->getMessage(), 'code' => $e->getCode()]));
        $response = $response->withHeader('Content-Type', 'application/json');
        $db = null;
        return $response;
    }
});

//GET COURSE
$app->get('/course/{courseId}', function (Request $request, Response $response, $args) {

    $db = new Db();

    $courseId = $request->getAttribute('courseId');
    try {

        $conn = $db->connect();
        $courses = $conn->query("SELECT * FROM coupons WHERE id=$courseId")->fetchAll();

        $response
            ->withStatus(200)
            ->withHeader("content-type", "application/json")
            ->getBody()
            ->write((string) json_encode([$courses]));

        return $response;

    } catch (PDOException $e) {
        $response->getBody()->write((string) json_encode(['text' => $e->getMessage(), 'code' => $e->getCode()]));
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response;
    }
    $db = null;
    return $response;
});

//ADD NEW COURSE
$app->post('/course/add', function (Request $request, Response $response, $args) {

    $requestParameters = json_decode($request->getBody(), true);
    $title = $requestParameters['title'];
    $couponCode = $requestParameters['couponCode'];
    $price = $requestParameters['price'];

    $db = new Db();
    try {

        $conn = $db->connect();
        $dbStatement = $conn->prepare("INSERT INTO coupons(title,couponCode,price) VALUES(:title,:couponCode,:price)");
        $dbStatement->bindParam("title", $title);
        $dbStatement->bindParam("couponCode", $couponCode, );
        $dbStatement->bindParam("price", $price);
        $result = $dbStatement->execute();
        if ($result) {
            $response
                ->withStatus(200)
                ->withHeader("content-type", "application/json")
                ->getBody()
                ->write((string) json_encode(["text" => "Insert Success"]));
        } else {
            $response
                ->withStatus(500)
                ->withHeader("content-type", "application/json")
                ->getBody()
                ->write((string) json_encode(["error" => "Insert Error."]));
        }
        $db = null;
        return $response;

    } catch (PDOException $e) {
        $response->getBody()->write((string) json_encode(['text' => $e->getMessage(), 'code' => $e->getCode()]));
        $response = $response->withHeader('Content-Type', 'application/json');
        $db = null;
        return $response;
    }
});

//UPDATE COURSE
$app->put('/course/update/{courseId}', function (Request $request, Response $response, $args) {

    $courseId = $request->getAttribute('courseId');
    if ($courseId) {
        $requestParameters = json_decode($request->getBody(), true);
        $title = $requestParameters['title'];
        $couponCode = $requestParameters['couponCode'];
        $price = $requestParameters['price'];
        $db = new Db();
        try {

            $conn = $db->connect();
            $dbStatement = $conn->prepare("UPDATE coupons SET title =:title,couponCode=:couponCode,price=:price WHERE id= $courseId");
            $dbStatement->bindParam("title", $title);
            $dbStatement->bindParam("couponCode", $couponCode, );
            $dbStatement->bindParam("price", $price);
            $result = $dbStatement->execute();
            if ($result) {
                $response
                    ->withStatus(200)
                    ->withHeader("content-type", "application/json")
                    ->getBody()
                    ->write((string) json_encode(["text" => "Update Success"]));
            } else {
                $response
                    ->withStatus(500)
                    ->withHeader("content-type", "application/json")
                    ->getBody()
                    ->write((string) json_encode(["error" => "Update Error."]));
            }
            $db = null;
            return $response;

        } catch (PDOException $e) {
            $response->getBody()->write((string) json_encode(['text' => $e->getMessage(), 'code' => $e->getCode()]));
            $response = $response->withHeader('Content-Type', 'application/json');
            $db = null;
            return $response;
        }

    } else {
        $response
            ->withStatus(500)
            ->withHeader("content-type", "application/json")
            ->getBody()
            ->write((string) json_encode(["error" => "Missing Parameter."]));
    }

    $db = null;
    return $response;

});

//DELETE COURSE
$app->delete('/course/{courseId}', function (Request $request, Response $response, $args) {

    $db = new Db();
    $courseId = $request->getAttribute('courseId');
    try {

        $conn = $db->connect();
        $dbStatement = $conn->prepare("DELETE FROM coupons WHERE id=$courseId");
        $result = $dbStatement->execute();

        if ($result) {
            $response
                ->withStatus(200)
                ->withHeader("content-type", "application/json")
                ->getBody()
                ->write((string) json_encode(["text" => "Delete Success"]));
        } else {
            $response
                ->withStatus(500)
                ->withHeader("content-type", "application/json")
                ->getBody()
                ->write((string) json_encode(["error" => "Delete Error."]));
        }
        $db = null;
        return $response;

    } catch (PDOException $e) {
        $response->getBody()->write((string) json_encode(['text' => $e->getMessage(), 'code' => $e->getCode()]));
        $response = $response->withHeader('Content-Type', 'application/json');
        $db = null;
        return $response;
    }
});