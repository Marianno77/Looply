# Looply

Looply is a task management calendar that allows users to create, track, and manage tasks with a focus on recurring tasks and detailed progress tracking.

## 🚀 Project Overview

Looply is a calendar-based task management system where users can add tasks that can be set to repeat based on user preferences. Each task generates task instances, allowing users to track the progress of each occurrence. The system automatically updates task statuses and generates task instances using scheduled commands.

## 📊 Database Structure

### Tasks Table

- **id**: Primary key.
- **user_id**: Foreign key linking to users.
- **name**: Task name.
- **description**: Optional task description.
- **repeat_type**: The type of recurrence (daily, weekly, monthly).
- **repeat_interval**: Interval for recurrence (e.g., every 2 days).
- **exclude_days**: Days of the week to exclude from scheduling.
- **exclude_holidays**: Whether holidays should be excluded.
- **start_date**: Start date for the task.
- **end_date**: Optional end date.
- **completed**: Whether the task is completed.

### Task Instances Table

- **id**: Primary key.
- **task_id**: Foreign key linking to the main task.
- **user_id**: Foreign key linking to users.
- **scheduled_for**: Date for this task instance.
- **completed_at**: Optional completion timestamp.
- **status**: Status of the task instance.

## ⚡ Main Features

- User registration and login.
- Creating, viewing, and managing tasks.
- Task recurrence options (daily, weekly, monthly).
- Exclusion of specific days and holidays.
- Automatic task instance creation and status updates.
- Progress tracking with charts (using JavaScript charting library).

## ⚙️ Automated Commands

- **task:update-status** - Updates task statuses to "not finished" if the scheduled date has passed.
- **task-instance-create** - Generates task instances based on user-defined rules.

## 🌐 Technologies Used

- **Laravel** (PHP Framework)
- **MySQL** (Database)
- **HTML, CSS, JavaScript** (Frontend)
- **JavaScript charting library for progress visualization**

---

# Looply

Looply to system zarządzania zadaniami oparty na kalendarzu, który pozwala użytkownikom tworzyć, śledzić i zarządzać zadaniami, koncentrując się na zadaniach cyklicznych oraz szczegółowym śledzeniu postępów.

## 🚀 Opis projektu

Looply to system zarządzania zadaniami oparty na kalendarzu, w którym użytkownicy mogą dodawać zadania, które mogą być ustawione do powtarzania na podstawie preferencji użytkownika. Każde zadanie generuje instancje zadań, pozwalając użytkownikom śledzić postęp każdego wystąpienia. System automatycznie aktualizuje statusy zadań i generuje instancje zadań za pomocą zaplanowanych komend.

## 📊 Struktura bazy danych

### Tabela Zadań

- **id**: Klucz główny.
- **user_id**: Klucz obcy łączący z użytkownikami.
- **name**: Nazwa zadania.
- **description**: Opcjonalny opis zadania.
- **repeat_type**: Typ powtarzania (codziennie, co tydzień, co miesiąc).
- **repeat_interval**: Interwał powtarzania (np. co 2 dni).
- **exclude_days**: Dni tygodnia, które mają być wyłączone z harmonogramu.
- **exclude_holidays**: Czy święta powinny być wyłączone.
- **start_date**: Data rozpoczęcia zadania.
- **end_date**: Opcjonalna data zakończenia.
- **completed**: Status zadania (czy zadanie zostało ukończone).

### Tabela Instancji Zadań

- **id**: Klucz główny.
- **task_id**: Klucz obcy łączący z głównym zadaniem.
- **user_id**: Klucz obcy łączący z użytkownikami.
- **scheduled_for**: Data zaplanowania instancji zadania.
- **completed_at**: Opcjonalny znacznik czasu ukończenia.
- **status**: Status instancji zadania.

## ⚡ Główne funkcje

- Rejestracja i logowanie użytkowników.
- Tworzenie, przeglądanie i zarządzanie zadaniami.
- Opcje cykliczności zadań (codziennie, co tydzień, co miesiąc).
- Wykluczanie określonych dni i świąt.
- Automatyczne tworzenie instancji zadań i aktualizacja statusów.
- Śledzenie postępów z wykorzystaniem wykresów (z użyciem biblioteki JavaScript do tworzenia wykresów).

## ⚙️ Automatyczne komendy

- **task:update-status** - Aktualizuje statusy zadań na "nieukończone", jeśli zaplanowana data minęła.
- **task-instance-create** - Generuje instancje zadań na podstawie reguł zdefiniowanych przez użytkownika.

## 🌐 Technologie

- **Laravel** (Framework PHP)
- **MySQL** (Baza danych)
- **HTML, CSS, JavaScript** (Frontend)
- **Biblioteka JavaScript do tworzenia wykresów** (Do wizualizacji postępów)

