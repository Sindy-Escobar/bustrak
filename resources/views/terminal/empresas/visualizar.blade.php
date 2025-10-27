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
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --purple-light: #9f7aea;
            --text-dark: #2d3748;
            --text-light: #718096;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
            --shadow-lg: 0 20px 40px rgba(102, 126, 234, 0.25);
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
            color: var(--purple-light);
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
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(159, 122, 234, 0.1);
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

        .loading {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid var(--border-color);
            border-top-color: var(--secondary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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

        .company-phone {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
        }

        .phone-icon {
            width: 16px;
            height: 16px;
            color: var(--purple-light);
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
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.2);
        }

        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
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

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .status-active {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-inactive {
            background: #fed7d7;
            color: #742a2a;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            background: #f7fafc;
            border-top: 1px solid var(--border-color);
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

        .btn-close {
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

        .btn-close:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
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
        <p class="company-count" id="companyCount">Total de empresas: 0</p>
    </div>

    <div id="companiesGrid" class="companies-grid">
        <div class="empty-state">
            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <p>No hay empresas registradas</p>
        </div>
    </div>
</div>

<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detalles de la Empresa</h2>
            <button class="close-btn" id="closeModal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalBody" class="modal-body"></div>
        <div class="modal-footer">
            <div class="action-buttons">
                <button class="btn-edit" id="editBtn">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </button>
                <button class="btn-validate" id="validateBtn">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Validar
                </button>
            </div>
            <button class="btn-close" id="closeModalBtn">Cerrar</button>
        </div>
    </div>
</div>

<script>
    if (typeof window !== 'undefined' && !window.storage) {
        window.storage = {
            async get(key) {
                const value = localStorage.getItem(key);
                return value ? { key, value } : null;
            },
            async list(prefix = '') {
                const keys = Object.keys(localStorage).filter(key => key.startsWith(prefix));
                return { keys };
            }
        };
    }

    let allCompanies = [];
    let currentCompanyId = null;

    document.addEventListener('DOMContentLoaded', () => {
        initializeApp();
        setupEventListeners();
    });

    function setupEventListeners() {
        document.getElementById('searchInput').addEventListener('input', handleSearch);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('closeModalBtn').addEventListener('click', closeModal);
        document.getElementById('editBtn').addEventListener('click', handleEdit);
        document.getElementById('validateBtn').addEventListener('click', handleValidate);
        document.getElementById('detailsModal').addEventListener('click', (e) => {
            if (e.target.id === 'detailsModal') closeModal();
        });
    }

    async function initializeApp() {
        await loadCompanies();
    }

    async function loadCompanies() {
        // DATOS DE PRUEBA - ELIMINAR DESPUÉS
        allCompanies = [
            {
                id: 1,
                name: "Transportes El Rey",
                phone: "+504 2234-5678",
                email: "contacto@elrey.hn",
                address: "Barrio El Centro, Tegucigalpa",
                rtn: "08019012345678",
                status: "active",
                registrationDate: "2024-01-15"
            },
            {
                id: 2,
                name: "Buses Norteños",
                phone: "+504 2245-6789",
                email: "info@nortenos.hn",
                address: "Colonia Kennedy, San Pedro Sula",
                rtn: "08019023456789",
                status: "active",
                registrationDate: "2024-02-20"
            },
            {
                id: 3,
                name: "Express del Valle",
                phone: "+504 2256-7890",
                email: "express@valle.hn",
                address: "Boulevard Morazán, Tegucigalpa",
                rtn: "08019034567890",
                status: "inactive",
                registrationDate: "2023-11-10"
            },
            {
                id: 4,
                name: "Rápidos del Sur",
                phone: "+504 2267-8901",
                email: "ventas@rapidossur.hn",
                address: "Barrio La Hoya, Choluteca",
                rtn: "08019045678901",
                status: "active",
                registrationDate: "2024-03-05"
            }
        ];
        displayCompanies(allCompanies);

        /* CÓDIGO ORIGINAL - DESCOMENTAR PARA USAR CON DATOS REALES
        try {
            const result = await window.storage.list('company:');

            if (result?.keys?.length > 0) {
                const companiesData = await Promise.all(
                    result.keys.map(async (key) => {
                        const data = await window.storage.get(key);
                        return data ? JSON.parse(data.value) : null;
                    })
                );
                allCompanies = companiesData.filter(c => c !== null);
                displayCompanies(allCompanies);
            } else {
                displayEmptyState();
            }
        } catch (error) {
            console.error('Error:', error);
        }
        */
    }

    function displayCompanies(companies) {
        const grid = document.getElementById('companiesGrid');
        const count = document.getElementById('companyCount');

        count.textContent = `Total de empresas: ${companies.length}`;

        if (companies.length === 0) {
            displayEmptyState('No se encontraron empresas');
            return;
        }

        grid.innerHTML = companies.map(c => `
                <div class="company-card">
                    <div class="company-header">
                        <svg class="company-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="company-name">${c.name}</h3>
                    </div>
                    <div class="company-info">
                        <div class="company-phone">
                            <svg class="phone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.163 21 3 14.837 3 7V6a2 2 0 012-1z"></path>
                            </svg>
                            <span>${c.phone || 'Sin teléfono'}</span>
                        </div>
                    </div>
                    <button class="btn-details" onclick='showDetails(${JSON.stringify(c)})'>
                        <svg class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver detalles
                    </button>
                </div>
            `).join('');
    }

    function displayEmptyState(message = 'No hay empresas registradas') {
        const grid = document.getElementById('companiesGrid');
        document.getElementById('companyCount').textContent = `Total de empresas: 0`;
        grid.innerHTML = `
            <div class="empty-state">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p>${message}</p>
            </div>`;
    }

    function handleSearch(e) {
        const term = e.target.value.toLowerCase();
        const filtered = allCompanies.filter(c =>
            c.name.toLowerCase().includes(term) || (c.phone && c.phone.includes(term))
        );
        displayCompanies(filtered);
    }

    function showDetails(company) {
        const modal = document.getElementById('detailsModal');
        const body = document.getElementById('modalBody');

        currentCompanyId = company.id;

        body.innerHTML = `
            <div class="detail-row">
                <div class="detail-label">Nombre de la Empresa</div>
                <div class="detail-value">${company.name}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Teléfono</div>
                <div class="detail-value">${company.phone || 'No especificado'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Correo Electrónico</div>
                <div class="detail-value">${company.email || 'No especificado'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Dirección</div>
                <div class="detail-value">${company.address || 'No especificada'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">RTN</div>
                <div class="detail-value">${company.rtn || 'No especificado'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Estado</div>
                <span class="status-badge ${company.status === 'active' ? 'status-active' : 'status-inactive'}">
                    ${company.status === 'active' ? 'Activo' : 'Inactivo'}
                </span>
            </div>
            <div class="detail-row">
                <div class="detail-label">Fecha de Registro</div>
                <div class="detail-value">${new Date(company.registrationDate).toLocaleDateString()}</div>
            </div>
        `;

        modal.classList.add('active');
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.remove('active');
        currentCompanyId = null;
    }

    function handleEdit() {
        if (currentCompanyId) {
            window.location.href = `/empresa-hu11/${currentCompanyId}/editar`;
        }
    }

    function handleValidate() {
        if (currentCompanyId) {
            window.location.href = `/empresa-hu11/${currentCompanyId}/validar`;
        }
    }
</script>
</body>
</html>
