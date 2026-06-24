import { useEffect, useState } from "react";

import MainLayout from "../layouts/MainLayout";

import { getHistory } from "../api/history";

import EmptyState from "../components/common/EmptyState";
import SkeletonCard from "../components/common/SkeletonCard";

export default function HistoryPage() {
    const [history, setHistory] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        loadHistory();
    }, []);

    async function loadHistory() {
        try {
            const data = await getHistory();

            setHistory(data || []);
        } catch {
            setHistory([]);
        } finally {
            setLoading(false);
        }
    }

    return (
        <MainLayout>
            <section className="page-section">
                <div className="page-header">
                    <h1>Riwayat Pencarian</h1>

                    <p>Semua filter pencarian film yang pernah digunakan.</p>
                </div>

                {loading && (
                    <div className="movie-grid">
                        {[...Array(6)].map((_, index) => (
                            <SkeletonCard key={index} />
                        ))}
                    </div>
                )}

                {!loading && history.length === 0 && (
                    <EmptyState
                        title="Belum Ada Riwayat"
                        message="Riwayat pencarian akan muncul di sini."
                    />
                )}

                {!loading && history.length > 0 && (
                    <div className="history-grid">
                        {history.map((item) => (
                            <div key={item.id} className="history-card">
                                <h3>Riwayat #{item.id}</h3>

                                <div className="history-item">
                                    <strong>Genre</strong>
                                    <span>{item.genre ?? "Semua Genre"}</span>
                                </div>

                                <div className="history-item">
                                    <strong>Tahun</strong>
                                    <span>{item.year ?? "Semua Tahun"}</span>
                                </div>

                                <div className="history-item">
                                    <strong>Rating</strong>
                                    <span>
                                        {item.min_rating ?? "Semua Rating"}
                                    </span>
                                </div>

                                <div className="history-item">
                                    <strong>Waktu</strong>
                                    <span>
                                        {new Date(
                                            item.searched_at,
                                        ).toLocaleString()}
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </section>
        </MainLayout>
    );
}
