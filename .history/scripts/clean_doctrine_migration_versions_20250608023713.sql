-- Script SQL pour nettoyer les entrées orphelines dans doctrine_migration_versions
DELETE FROM doctrine_migration_versions WHERE version = 'DoctrineMigrations\\VersionAddCartonsToClassementPoule';
DELETE FROM doctrine_migration_versions WHERE version = 'DoctrineMigrations\\VersionAddGagnesNulsPerdusToClassementPoule';
