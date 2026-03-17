# Reactive Event Log

A high-performance Vue 3 dashboard built to process, sanitize, and visualize standardized game data strings (PGN) in a real-time reactive environment.

## Overview
This application serves as a technical demonstration of modern frontend state management and asynchronous data processing. It transforms raw, coordinate-based data streams into a fluid, human-readable visual experience using the latest web technologies.

## Key Features
- **Dynamic Data Injection**: Parses raw PGN strings into a structured, chronological event log.
- **Asynchronous Replay Engine**: Simulates live data feeds with millisecond precision, demonstrating control over timing and execution loops.
- **Reactive UI/UX**: Built with the **Vue 3 Composition API** to ensure fluid, auto-scrolling updates and high-fps performance.
- **Event-Driven Notifications**: Synchronized audio/visual feedback triggered by critical data milestones within the stream.

## Tech Stack
- **Frontend**: Vue 3 (Composition API), TypeScript, Tailwind CSS
- **Backend/Routing**: Laravel 11, Inertia.js
- **Build Tooling**: Vite, PHP 8.2+
- **Version Control**: Git (Conventional Commits)

## Local Development Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/RAD-AgileDevOps/reactive-event-log.git
   ```

2. **Install Backend Dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   ```bash
   cp .env.example .env && php artisan key:generate
   ```

5. **Run the Development Server:**
   ```bash
   php artisan serve
   # In a separate terminal:
   npm run dev
   ```

