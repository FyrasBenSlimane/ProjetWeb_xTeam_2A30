<?php
session_start();
require_once '../config/database.php';
require_once 'check_admin.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Récupérer les filtres
$event_id = $_GET['event'] ?? null;
$status = $_GET['status'] ?? null;

// Construire la requête
$query = "
    SELECT 
        p.name,
        p.email,
        p.phone,
        e.title as event_title,
        e.date as event_date,
        e.location,
        p.status,
        p.registration_date,
        p.admin_notes
    FROM participants p
    JOIN events e ON p.event_id = e.id
    WHERE 1=1
";

$params = [];

if ($event_id && $event_id !== 'all') {
    $query .= " AND p.event_id = ?";
    $params[] = $event_id;
}

if ($status && $status !== 'all') {
    $query .= " AND p.status = ?";
    $params[] = $status;
}

$query .= " ORDER BY p.registration_date DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Définir les en-têtes pour le téléchargement CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=participants_' . date('Y-m-d_His') . '.csv');

// Créer le fichier CSV
$output = fopen('php://output', 'w');

// Ajouter le BOM UTF-8 pour Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// En-têtes des colonnes
fputcsv($output, [
    'Nom',
    'Email',
    'Téléphone',
    'Événement',
    'Date événement',
    'Lieu',
    'Statut',
    "Date d'inscription",
    'Notes'
]);

// Données
foreach ($participants as $participant) {
    fputcsv($output, [
        $participant['name'],
        $participant['email'],
        $participant['phone'],
        $participant['event_title'],
        date('d/m/Y', strtotime($participant['event_date'])),
        $participant['location'],
        $participant['status'],
        date('d/m/Y H:i', strtotime($participant['registration_date'])),
        $participant['admin_notes']
    ]);
}

fclose($output);