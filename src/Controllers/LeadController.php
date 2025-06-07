 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a/src/Controllers/LeadController.php b/src/Controllers/LeadController.php
index 83532f2464d5dbbbc6f9959808ebabfb6629e350..f7e4c416b7b343835929ec1d72dec3eb0f0b7092 100644
--- a/src/Controllers/LeadController.php
+++ b/src/Controllers/LeadController.php
@@ -3,27 +3,65 @@ declare(strict_types=1);
 
 namespace App\Controllers;
 
 use Psr\Http\Message\ResponseInterface as Response;
 use Psr\Http\Message\ServerRequestInterface as Request;
 use App\Models\Lead;
 
 class LeadController
 {
     public function index(Request $req, Response $res): Response
     {
         $leads = Lead::all();
         $res->getBody()->write($leads->toJson());
         return $res->withHeader('Content-Type', 'application/json');
     }
 
     public function store(Request $req, Response $res): Response
     {
         $data = (array)$req->getParsedBody();
         $lead = Lead::create($data);
         // тут можно вызвать сервис уведомлений
         $res->getBody()->write($lead->toJson());
         return $res->withStatus(201)->withHeader('Content-Type', 'application/json');
     }
 
-    // show, update, delete — аналогично
+    public function show(Request $req, Response $res, array $args): Response
+    {
+        $lead = Lead::find($args['id']);
+        if (!$lead) {
+            $res->getBody()->write(json_encode(['message' => 'Lead not found']));
+            return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
+        }
+
+        $res->getBody()->write($lead->toJson());
+        return $res->withHeader('Content-Type', 'application/json');
+    }
+
+    public function update(Request $req, Response $res, array $args): Response
+    {
+        $lead = Lead::find($args['id']);
+        if (!$lead) {
+            $res->getBody()->write(json_encode(['message' => 'Lead not found']));
+            return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
+        }
+
+        $data = (array)$req->getParsedBody();
+        $lead->fill($data);
+        $lead->save();
+
+        $res->getBody()->write($lead->toJson());
+        return $res->withHeader('Content-Type', 'application/json');
+    }
+
+    public function delete(Request $req, Response $res, array $args): Response
+    {
+        $lead = Lead::find($args['id']);
+        if (!$lead) {
+            $res->getBody()->write(json_encode(['message' => 'Lead not found']));
+            return $res->withStatus(404)->withHeader('Content-Type', 'application/json');
+        }
+
+        $lead->delete();
+        return $res->withStatus(204);
+    }
 }
 
EOF
)
