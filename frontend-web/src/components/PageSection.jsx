export default function PageSection({ title, description, action, children }) {
  return (
    <section className="page-section">
      <header className="section-header">
        <div>
          <h2>{title}</h2>
          {description ? <p>{description}</p> : null}
        </div>
        {action}
      </header>
      {children}
    </section>
  );
}
