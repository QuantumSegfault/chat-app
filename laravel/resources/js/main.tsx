import '@/bootstrap';

import { createRoot } from 'react-dom/client';
import React from 'react';

import App from '@/components/App';

const container = document.querySelector('#app');

if (container) {
    const root = createRoot(container);

    root.render(
        <React.StrictMode>
            <App />
        </React.StrictMode>,
    );
} else {
    console.error('React mounting failed: Root element #app not found.');
}
