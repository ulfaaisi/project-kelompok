import { useEffect, useState } from "react";

const API_URL = import.meta.env.VITE_API_URL;

export default function HistoryPage() {
    const [history, setHistory] = useState([]);

    useEffect(() => {
        loadHistory();
    }, []);

    async function loadHistory() {
        const response = await fetch(`${API_URL}/api/history`, {
            credentials: "include",
        });

        const result = await response.json();

        setHistory(result.data || []);
    }

    return (
        <section className="container">
            <h1>Riwayat</h1>

            {history.length === 0 && <p>Belum ada riwayat tontonan.</p>}
        </section>
    );
}
