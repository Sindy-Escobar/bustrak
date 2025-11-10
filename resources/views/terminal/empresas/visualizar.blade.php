<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTrak - Visualizar Empresas de Buses</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%);
            --primary-color: #1976d2;
            --secondary-color: #1565c0;
            --text-dark: #2d3748;
            --text-light: #718096;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --shadow: 0 10px 25px rgba(25, 118, 210, 0.15);
            --shadow-lg: 0 20px 40px rgba(25, 118, 210, 0.25);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .icon-building {
            width: 48px;
            height: 48px;
            color: var(--secondary-color);
            flex-shrink: 0;
        }

        .header-card h1 {
            font-size: 2rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--text-light);
        }

        .search-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
        }

        .search-box {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #42a5f5;
        }

        .search-box input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        .company-count {
            margin-top: 1rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .companies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .empty-state {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem;
            background: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .company-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .company-header {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .company-icon {
            width: 24px;
            height: 24px;
            color: var(--secondary-color);
            flex-shrink: 0;
        }

        .company-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .company-info {
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .info-icon {
            width: 16px;
            height: 16px;
            color: #42a5f5;
        }

        .btn-details {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--primary-gradient);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(25, 118, 210, 0.2);
        }

        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(25, 118, 210, 0.3);
        }

        .eye-icon {
            width: 18px;
            height: 18px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: 20px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            background: var(--primary-gradient);
            color: var(--white);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: var(--white);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
        }

        .detail-row {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-size: 1rem;
            color: var(--text-dark);
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            background: #f7fafc;
            border-top: 1px solid var(--border-color);
        }

        .btn-close-modal {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary-gradient);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close-modal:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(25, 118, 210, 0.3);
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .btn-edit {
            padding: 0.875rem;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-validate {
            padding: 0.875rem;
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-validate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(246, 173, 85, 0.3);
        }

        @media (max-width: 768px) {
            body { padding: 1rem; }
            .header-card h1 { font-size: 1.5rem; }
            .companies-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-card">
        <div class="header-content">
            <svg class="icon-building" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <div>
                <h1>Empresas de Buses Registradas</h1>
                <p class="subtitle">Visualiza todas las empresas de transporte registradas en el sistema</p>
            </div>
        </div>
    </div>

    <div class="search-card">
        <div class="search-box">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Buscar empresa por nombre..." autocomplete="off">
        </div>
        <p class="company-count">Total de empresas: {{ count($empresas) }}</p>
    </div>

    <div id="companiesGrid" class="companies-grid">
        @forelse($empresas as $empresa)
            <div class="company-card" data-search="{{ strtolower($empresa->nombre . ' ' . $empresa->telefono) }}">
                <div class="company-header">
                    <svg class="company-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="company-name">{{ $empresa->nombre }}</h3>
                </div>
                <div class="company-info">
                    <div class="info-row">
                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.163 21 3 14.837 3 7V6a2 2 0 012-1z"></path>
                        </svg>
                        <span>{{ $empresa->telefono }}</span>
                    </div>
                    @if($empresa->propietario)
                        <div class="info-row">
                            <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $empresa->propietario }}</span>
                        </div>
                    @endif
                </div>
                <button class="btn-details" onclick='showDetails(@json($empresa))'>
                    <svg class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Ver detalles
                </button>
            </div>
        @empty
            <div class="empty-state">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p>No hay empresas registradas</p>
            </div>
        @endforelse
    </div>
</div>

<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detalles de la Empresa</h2>
            <button class="close-btn" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalBody" class="modal-body"></div>
        <div class="modal-footer">
            <div class="action-buttons">
                <button class="btn-edit" onclick="editCompany()">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </button>
                <button class="btn-validate" onclick="validateCompany()">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Validar
                </button>
            </div>
            <button class="btn-close-modal" onclick="closeModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    let currentEmpresaId = null;

    // Búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.company-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const searchData = card.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Actualizar contador
        document.querySelector('.company-count').textContent = `Empresas encontradas: ${visibleCount}`;
    });

    // Mostrar detalles en modal
    function showDetails(empresa) {
        const modal = document.getElementById('detailsModal');
        const body = document.getElementById('modalBody');

        // Guardar el ID de la empresa actual
        currentEmpresaId = empresa.id;

        body.innerHTML = `
            <div class="detail-row">
                <div class="detail-label">Nombre de la Empresa</div>
                <div class="detail-value">${empresa.nombre}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Propietario</div>
                <div class="detail-value">${empresa.propietario || 'No especificado'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Teléfono</div>
                <div class="detail-value">${empresa.telefono}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Correo Electrónico</div>
                <div class="detail-value">${empresa.email || 'No especificado'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Dirección</div>
                <div class="detail-value">${empresa.direccion}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Fecha de Registro</div>
                <div class="detail-value">${new Date(empresa.created_at).toLocaleDateString('es-HN')}</div>
            </div>
        `;

        modal.classList.add('active');
    }

    // Cerrar modal
    function closeModal() {
        document.getElementById('detailsModal').classList.remove('active');
        currentEmpresaId = null;
    }

    // Editar empresa
    function editCompany() {
        if (currentEmpresaId) {
            window.location.href = `/empresa-hu11/${currentEmpresaId}/editar`;
        }
    }

    // Validar empresa
    function validateCompany() {
        if (currentEmpresaId) {
            window.location.href = `/validar-empresas`;
        }
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('detailsModal').addEventListener('click', function(e) {
        if (e.target.id === 'detailsModal') {
            closeModal();
        }
    });

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
</body>
</html>
