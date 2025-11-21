<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\MaterialDidacticoController;
use App\Http\Controllers\SuscripcionController;

use App\Http\Controllers\CompraController;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PlanEstudioController;
use App\Http\Controllers\IntegracionIaController;


Route::get('/', function () {
    return view('client.home.index');
})->name('home');

//Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('auth')->group(function () {
    Route::get('/signin-index', function () {
        return view('auth.login');
    })->name('singin');

    Route::get('/signup-index', function () {
        return view('auth.register');
    })->name('singup');
});

// Rutas de cursos
Route::prefix('courses')->group(function () {
    Route::get('/index-cursos', [CursoController::class, 'cursosshow'])->name('courses.index');
    Route::get('/curso/{id}', [CursoController::class, 'show'])->name('curso.detalle');

});

// Ruta de registro

//Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');



// Rutas para administradores
Route::middleware('roles:admin')->group(function () {
    Route::get('/administrador', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');

    Route::get('/admin/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');

    /* crud de usuarios */
    Route::resource('usuarios', AdminController::class);

    //rutas de secciones

    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.secciones.usuarios');
    Route::get('/renovaciones', [AdminController::class, 'renovaciones'])->name('admin.secciones.renovaciones');
    Route::get('/subcripciones', [AdminController::class, 'subscripciones'])->name('admin.secciones.subscripciones');

    // Más rutas para el rol admin...

    /*crud categoria */
    Route::resource('categorias', CategoriaController::class);

    Route::get('/curso-admin',[CursoController::class,'indexAdmin'])->name('admin.secciones.CursoCrud');

    

});

// Rutas para clientes
Route::middleware('roles:cliente')->group(function () {
    Route::get('/usuario', function () {
        return view('client.home.index');
    })->name('client.home');

    Route::get('/usuario/profile', function () {
        return view('client.profile');
    })->name('client.profile');

    Route::get('/usuario/orders', function () {
        return view('client.orders');
    })->name('client.orders');


    //crud curso para cliente

    Route::get('/curso-show',[CursoController::class,'index'])->name('client.courses.create');
    Route::get('/mis-cursos',[CursoController::class,'misCursos'])->name('mis-cursos');
    Route::post('/curso-store',[CursoController::class,'store'])->name('cursos.store');
    // Más rutas para el rol cliente...


    //material didactico

Route::get('cursos/{cursoId}/materiales', [MaterialDidacticoController::class, 'verMateriales'])->name('materiales.ver');

Route::post('/material-didactico/store', [MaterialDidacticoController::class, 'store'])->name('material.create');


Route::post('cursos/{cursoId}/materiales', [MaterialDidacticoController::class, 'guardarMaterial'])->name('materiales.guardar');




Route::get('/curso/{id}/detalles', [CursoController::class, 'detalles'])->name('curso.detalles');
//Route::get('/curso/{id}/detalles', [CursoController::class, 'show'])->name('curso.show');

    Route::get('/curso/{id}/comprar', [CursoController::class, 'comprar'])->name('curso.comprar');



});

Route::get('plan', [SuscripcionController::class, 'plan'])->name('plan');
Route::get('/stripe/{precio}', [SuscripcionController::class, 'stripe']);
Route::post('stripe/{precio}', [SuscripcionController::class, 'stripePost'])
    ->name('stripe.post');


Route::get('/bitacora', [SuscripcionController::class, 'bitacora']);

Route::get('/estadistica', [SuscripcionController::class, 'estadistica']);

Route::get('mis/suscripciones', [SuscripcionController::class, 'suscripciones'])->name('suscripciones');

Route::get('compra', [CompraController::class, 'compra'])->name('compra');



//-------------------------------plan de estudio
Route::get('/plan-estudio/create', [PlanEstudioController::class, 'create'])->name('plan_estudio.create');
//Route::resource('/plan-estudio', PlanEstudioController::class)->names('plan_estudio');
//Route::get('/plan-estudio/{id}', [PlanEstudioController::class, 'show'])->name('plan_estudio.show');
Route::post('/plan-estudio/generar', [PlanEstudioController::class, 'generarPlandeestudio'])->name('plan_estudio.generar');
    // IA: Upload Media y vista para estudiantes
    Route::get('/ia/media', [IntegracionIaController::class, 'vistaUpload'])->name('client.apuntes.ia.media');
    Route::post('/ia/media/upload', [IntegracionIaController::class, 'upload'])->name('client.ia.media.upload');
    Route::get('/ia/media/{id}/status', [IntegracionIaController::class, 'status'])->name('client.ia.media.status');

    // Apuntes: índice y detalle
    Route::get('/apuntes', [IntegracionIaController::class, 'apuntesIndex'])->name('client.apuntes.index');
    Route::get('/apuntes/media/{id}', [IntegracionIaController::class, 'apuntesShow'])->name('client.apuntes.show');
    Route::get('/apuntes/media/{id}/temas', [IntegracionIaController::class, 'temasList'])->name('client.apuntes.temas');
    Route::post('/apuntes/media/{id}/temas/generar', [IntegracionIaController::class, 'temasGenerar'])->name('client.apuntes.temas.generar');
    Route::post('/apuntes/temas/{temaId}/profundizar', [IntegracionIaController::class, 'temasProfundizar'])->name('client.apuntes.temas.profundizar');
    Route::post('/apuntes/temas/{temaId}/secciones', [IntegracionIaController::class, 'temasAddSeccion'])->name('client.apuntes.temas.secciones');
    Route::post('/apuntes/{apunteId}/quizzes/generar', [IntegracionIaController::class, 'quizzesGenerate'])->name('client.apuntes.quizzes.generar');
    Route::post('/apuntes/quizzes/{quizId}/sesiones', [IntegracionIaController::class, 'sesionCrear'])->name('client.apuntes.quizzes.sesion');
    Route::get('/apuntes/quizzes/{quizId}/recomendaciones', [IntegracionIaController::class, 'recomendacionesQuiz'])->name('client.apuntes.quizzes.recomendaciones');
    Route::get('/apuntes/sesiones/{sesionId}', [IntegracionIaController::class, 'sesionVer'])->name('client.apuntes.sesion.ver');
    Route::post('/apuntes/sesiones/{sesionId}/responder', [IntegracionIaController::class, 'sesionResponder'])->name('client.apuntes.sesion.responder');
