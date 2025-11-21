<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extensiones y tipos ENUM necesarios
        DB::statement('CREATE EXTENSION IF NOT EXISTS "vector"');

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'media_tipo_enum') THEN CREATE TYPE \"public\".\"media_tipo_enum\" AS ENUM('VIDEO', 'AUDIO'); END IF; END $$;");
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'media_estado_procesamiento_enum') THEN CREATE TYPE \"public\".\"media_estado_procesamiento_enum\" AS ENUM('pendiente', 'procesando', 'completado', 'error'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'transcripcion_estado_ia_enum') THEN CREATE TYPE \"public\".\"transcripcion_estado_ia_enum\" AS ENUM('pendiente', 'procesando', 'completado', 'error'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'apunte_ia_tipo_enum') THEN CREATE TYPE \"public\".\"apunte_ia_tipo_enum\" AS ENUM('resumen', 'mapa', 'explicacion', 'flashcard'); END IF; END $$;");
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'apunte_ia_estadoia_enum') THEN CREATE TYPE \"public\".\"apunte_ia_estadoia_enum\" AS ENUM('pendiente', 'procesando', 'completado', 'error'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'tema_ia_origen_enum') THEN CREATE TYPE \"public\".\"tema_ia_origen_enum\" AS ENUM('ia', 'usuario', 'mixto'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'sesion_estudio_estado_enum') THEN CREATE TYPE \"public\".\"sesion_estudio_estado_enum\" AS ENUM('en_progreso', 'completada', 'abandonada'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'quiz_ia_tipo_enum') THEN CREATE TYPE \"public\".\"quiz_ia_tipo_enum\" AS ENUM('multiple', 'abierta', 'mixto'); END IF; END $$;");
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'quiz_ia_dificultad_enum') THEN CREATE TYPE \"public\".\"quiz_ia_dificultad_enum\" AS ENUM('facil', 'media', 'dificil'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'pregunta_ia_tipo_enum') THEN CREATE TYPE \"public\".\"pregunta_ia_tipo_enum\" AS ENUM('opcion_multiple', 'abierta'); END IF; END $$;");

        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'embedding_ia_tipocontenido_enum') THEN CREATE TYPE \"public\".\"embedding_ia_tipocontenido_enum\" AS ENUM('resumen', 'explicacion', 'flashcard', 'tema', 'seccion'); END IF; END $$;");
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'embedding_ia_tipoentidad_enum') THEN CREATE TYPE \"public\".\"embedding_ia_tipoentidad_enum\" AS ENUM('transcripcion', 'tema'); END IF; END $$;");

        // Tablas
        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "quiz_ia" (
            "id_quiz" SERIAL PRIMARY KEY,
            "tipo" "public"."quiz_ia_tipo_enum" NOT NULL DEFAULT 'multiple',
            "dificultad" "public"."quiz_ia_dificultad_enum" NOT NULL DEFAULT 'media',
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "id_apunte" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "sesion_estudio" (
            "id_sesion" SERIAL PRIMARY KEY,
            "fecha_inicio" TIMESTAMP NOT NULL DEFAULT now(),
            "duracion_total" INTEGER,
            "progreso" NUMERIC(5,2) NOT NULL DEFAULT '0',
            "resultado_evaluacion" NUMERIC(5,2) NOT NULL DEFAULT '0',
            "total_preguntas" INTEGER NOT NULL DEFAULT '0',
            "preguntas_respondidas" INTEGER NOT NULL DEFAULT '0',
            "preguntas_correctas" INTEGER NOT NULL DEFAULT '0',
            "estado" "public"."sesion_estudio_estado_enum" NOT NULL DEFAULT 'en_progreso',
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "usuarioId" INTEGER,
            "quizId" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "media" (
            "id_media" SERIAL PRIMARY KEY,
            "titulo" VARCHAR(200) NOT NULL,
            "descripcion" TEXT,
            "tipo" "public"."media_tipo_enum" NOT NULL,
            "url_archivo" TEXT NOT NULL,
            "ruta_audio" TEXT,
            "estado_procesamiento" "public"."media_estado_procesamiento_enum" NOT NULL DEFAULT 'pendiente',
            "duracion_segundos" INTEGER,
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "usuarioId" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "transcripcion" (
            "id_transcripcion" SERIAL PRIMARY KEY,
            "texto" TEXT,
            "idioma" VARCHAR(10) NOT NULL DEFAULT 'es',
            "duracion_segundos" INTEGER,
            "estado_ia" "public"."transcripcion_estado_ia_enum" NOT NULL DEFAULT 'pendiente',
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "videoId" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "apunte_ia" (
            "id_apunte" SERIAL PRIMARY KEY,
            "titulo" VARCHAR(200),
            "contenido" TEXT NOT NULL,
            "tipo" "public"."apunte_ia_tipo_enum",
            "estadoIA" "public"."apunte_ia_estadoia_enum" NOT NULL DEFAULT 'pendiente',
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "userId" INTEGER,
            "transcripcionId" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "tema_ia" (
            "id_tema" SERIAL PRIMARY KEY,
            "titulo_tema" VARCHAR(200) NOT NULL,
            "descripcion" TEXT,
            "contenido" TEXT,
            "estructura" JSONB,
            "nivel_profundidad" SMALLINT NOT NULL DEFAULT '1',
            "origen" "public"."tema_ia_origen_enum" NOT NULL DEFAULT 'ia',
            "orden" SMALLINT NOT NULL DEFAULT '0',
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "id_tema_padre" INTEGER,
            "id_apunte" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "pregunta_ia" (
            "id_pregunta" SERIAL PRIMARY KEY,
            "enunciado" TEXT NOT NULL,
            "tipo" "public"."pregunta_ia_tipo_enum" NOT NULL DEFAULT 'opcion_multiple',
            "respuesta_correcta" INTEGER,
            "respuesta_esperada" TEXT,
            "opciones" JSONB,
            "explicacion" TEXT,
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "quizId" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "respuesta_usuario" (
            "id_respuesta" SERIAL PRIMARY KEY,
            "respuesta_usuario" TEXT,
            "correcta" BOOLEAN,
            "puntuacion" NUMERIC(5,2) NOT NULL DEFAULT '0',
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "preguntaId" INTEGER,
            "usuarioId" INTEGER,
            "sesionId" INTEGER
        )
        SQL);

        DB::statement(<<<SQL
        CREATE TABLE IF NOT EXISTS "embedding_ia" (
            "id_embedding" SERIAL PRIMARY KEY,
            "vector" vector,
            "tipoContenido" "public"."embedding_ia_tipocontenido_enum",
            "tipoEntidad" "public"."embedding_ia_tipoentidad_enum",
            "textoOriginal" TEXT,
            "metadata" JSONB,
            "createdAt" TIMESTAMP NOT NULL DEFAULT now(),
            "updatedAt" TIMESTAMP NOT NULL DEFAULT now(),
            "deletedAt" TIMESTAMP,
            "temaId" INTEGER
        )
        SQL);

        // Comentarios en columnas (opcionales)
        DB::statement("COMMENT ON COLUMN \"tema_ia\".\"estructura\" IS 'Estructura del tema con secciones organizadas'");
        DB::statement("COMMENT ON COLUMN \"tema_ia\".\"nivel_profundidad\" IS 'Nivel de profundidad del tema (1=básico, 2=intermedio, 3=avanzado)'");
        DB::statement("COMMENT ON COLUMN \"tema_ia\".\"origen\" IS 'Origen del contenido del tema'");
        DB::statement("COMMENT ON COLUMN \"tema_ia\".\"orden\" IS 'Orden del tema dentro del apunte'");

        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"duracion_total\" IS 'en segundos'");
        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"progreso\" IS 'Porcentaje de avance (0-100)'");
        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"resultado_evaluacion\" IS 'Porcentaje de aciertos (0-100)'");
        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"total_preguntas\" IS 'Total de preguntas del quiz'");
        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"preguntas_respondidas\" IS 'Cantidad de preguntas respondidas'");
        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"preguntas_correctas\" IS 'Cantidad de respuestas correctas'");
        DB::statement("COMMENT ON COLUMN \"sesion_estudio\".\"estado\" IS 'Estado actual de la sesión'");

        // Llaves foráneas
        DB::statement("ALTER TABLE \"media\" ADD CONSTRAINT \"FK_media_usuario\" FOREIGN KEY (\"usuarioId\") REFERENCES \"usuarios\"(\"id\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"transcripcion\" ADD CONSTRAINT \"FK_transcripcion_media\" FOREIGN KEY (\"videoId\") REFERENCES \"media\"(\"id_media\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"apunte_ia\" ADD CONSTRAINT \"FK_apunte_usuario\" FOREIGN KEY (\"userId\") REFERENCES \"usuarios\"(\"id\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"apunte_ia\" ADD CONSTRAINT \"FK_apunte_transcripcion\" FOREIGN KEY (\"transcripcionId\") REFERENCES \"transcripcion\"(\"id_transcripcion\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"tema_ia\" ADD CONSTRAINT \"FK_tema_padre\" FOREIGN KEY (\"id_tema_padre\") REFERENCES \"tema_ia\"(\"id_tema\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"tema_ia\" ADD CONSTRAINT \"FK_tema_apunte\" FOREIGN KEY (\"id_apunte\") REFERENCES \"apunte_ia\"(\"id_apunte\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"quiz_ia\" ADD CONSTRAINT \"FK_quiz_apunte\" FOREIGN KEY (\"id_apunte\") REFERENCES \"apunte_ia\"(\"id_apunte\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"sesion_estudio\" ADD CONSTRAINT \"FK_sesion_usuario\" FOREIGN KEY (\"usuarioId\") REFERENCES \"usuarios\"(\"id\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"sesion_estudio\" ADD CONSTRAINT \"FK_sesion_quiz\" FOREIGN KEY (\"quizId\") REFERENCES \"quiz_ia\"(\"id_quiz\") ON DELETE SET NULL");
        DB::statement("ALTER TABLE \"pregunta_ia\" ADD CONSTRAINT \"FK_pregunta_quiz\" FOREIGN KEY (\"quizId\") REFERENCES \"quiz_ia\"(\"id_quiz\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"respuesta_usuario\" ADD CONSTRAINT \"FK_respuesta_pregunta\" FOREIGN KEY (\"preguntaId\") REFERENCES \"pregunta_ia\"(\"id_pregunta\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"respuesta_usuario\" ADD CONSTRAINT \"FK_respuesta_usuario\" FOREIGN KEY (\"usuarioId\") REFERENCES \"usuarios\"(\"id\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"respuesta_usuario\" ADD CONSTRAINT \"FK_respuesta_sesion\" FOREIGN KEY (\"sesionId\") REFERENCES \"sesion_estudio\"(\"id_sesion\") ON DELETE CASCADE");
        DB::statement("ALTER TABLE \"embedding_ia\" ADD CONSTRAINT \"FK_embedding_tema\" FOREIGN KEY (\"temaId\") REFERENCES \"tema_ia\"(\"id_tema\") ON DELETE CASCADE");
    }

    public function down(): void
    {
        // El orden inverso para soltar FKs y tablas
        DB::statement('ALTER TABLE "embedding_ia" DROP CONSTRAINT IF EXISTS "FK_embedding_tema"');
        DB::statement('ALTER TABLE "respuesta_usuario" DROP CONSTRAINT IF EXISTS "FK_respuesta_sesion"');
        DB::statement('ALTER TABLE "respuesta_usuario" DROP CONSTRAINT IF EXISTS "FK_respuesta_usuario"');
        DB::statement('ALTER TABLE "respuesta_usuario" DROP CONSTRAINT IF EXISTS "FK_respuesta_pregunta"');
        DB::statement('ALTER TABLE "sesion_estudio" DROP CONSTRAINT IF EXISTS "FK_sesion_quiz"');
        DB::statement('ALTER TABLE "sesion_estudio" DROP CONSTRAINT IF EXISTS "FK_sesion_usuario"');
        DB::statement('ALTER TABLE "pregunta_ia" DROP CONSTRAINT IF EXISTS "FK_pregunta_quiz"');
        DB::statement('ALTER TABLE "quiz_ia" DROP CONSTRAINT IF EXISTS "FK_quiz_apunte"');
        DB::statement('ALTER TABLE "tema_ia" DROP CONSTRAINT IF EXISTS "FK_tema_apunte"');
        DB::statement('ALTER TABLE "tema_ia" DROP CONSTRAINT IF EXISTS "FK_tema_padre"');
        DB::statement('ALTER TABLE "apunte_ia" DROP CONSTRAINT IF EXISTS "FK_apunte_transcripcion"');
        DB::statement('ALTER TABLE "apunte_ia" DROP CONSTRAINT IF EXISTS "FK_apunte_usuario"');
        DB::statement('ALTER TABLE "transcripcion" DROP CONSTRAINT IF EXISTS "FK_transcripcion_media"');
        DB::statement('ALTER TABLE "media" DROP CONSTRAINT IF EXISTS "FK_media_usuario"');

        DB::statement('DROP TABLE IF EXISTS "embedding_ia"');
        DB::statement('DROP TABLE IF EXISTS "respuesta_usuario"');
        DB::statement('DROP TABLE IF EXISTS "pregunta_ia"');
        DB::statement('DROP TABLE IF EXISTS "tema_ia"');
        DB::statement('DROP TABLE IF EXISTS "apunte_ia"');
        DB::statement('DROP TABLE IF EXISTS "transcripcion"');
        DB::statement('DROP TABLE IF EXISTS "media"');
        DB::statement('DROP TABLE IF EXISTS "quiz_ia"');

        DB::statement('DROP TYPE IF EXISTS "public"."embedding_ia_tipoentidad_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."embedding_ia_tipocontenido_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."pregunta_ia_tipo_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."quiz_ia_dificultad_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."quiz_ia_tipo_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."sesion_estudio_estado_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."tema_ia_origen_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."apunte_ia_estadoia_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."apunte_ia_tipo_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."transcripcion_estado_ia_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."media_estado_procesamiento_enum"');
        DB::statement('DROP TYPE IF EXISTS "public"."media_tipo_enum"');
    }
};