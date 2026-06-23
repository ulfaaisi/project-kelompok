export default function CastList({ cast = [] }) {
    return (
        <section className="cast-list">
            <h3>Pemeran Utama</h3>

            {cast.map((person) => (
                <div key={person.id} className="cast-item">
                    <strong>{person.name}</strong>

                    <span>{person.character}</span>
                </div>
            ))}
        </section>
    );
}
