import { useState } from "react";

const CARDS = [
  {
    num: 1,
    title: "Redes Sociales",
    body: "Gestionamos tus redes sociales con estrategia, contenido de calidad y análisis de resultados para hacer crecer tu comunidad y mejorar el engagement de tu marca.",
  },
  {
    num: 2,
    title: "Reputación online",
    body: "Analizamos cuál es la situación actual de tu marca y la huella digital. Realizamos auditorías de ORM iniciales, esenciales para elaborar una estrategia óptima de reputación de marca en Internet.",
  },
  {
    num: 3,
    title: "Consultoría digital",
    body: "Analizamos la situación actual de la marca y elaboramos una consultoría digital con conclusiones de mejora para tu negocio. Contamos con profesionales expertos en agregar valor y escalar el crecimiento de tu negocio.",
  },
  {
    num: 4,
    title: "Campañas de publicidad",
    body: "Creamos una estrategia de publicidad con objetivos medibles (KPIs). Definimos los formatos, las redes y los canales según necesidades y presupuesto disponible.",
  },
  {
    num: 5,
    title: "SEO & Contenidos",
    body: "Optimizamos tu presencia en buscadores con estrategias SEO técnicas y de contenido. Creamos contenido relevante que atrae tráfico cualificado y posiciona tu marca como referente en tu sector.",
  },
];

export function DesktopCarousel() {
  // offset = number of cards closed as narrow strips on the left
  // starts at 1 → card 0 starts closed
  const [offset, setOffset] = useState(1);
  const total = CARDS.length;

  const handleCardClick = (idx: number, isClosed: boolean) => {
    if (isClosed) {
      // clicking a closed strip → re-open from here
      setOffset(idx);
    } else {
      // clicking open card → collapse it
      setOffset(idx + 1);
    }
  };

  return (
    <div
      style={{
        fontFamily: "'Poppins', sans-serif",
        background: "#0098ED",
        padding: "60px 0 52px",
        overflow: "hidden",
        minHeight: "100vh",
      }}
    >
      <div style={{ maxWidth: "100%", margin: "0 auto", paddingLeft: 80 }}>
        {/* Track */}
        <div style={{ overflow: "hidden", marginBottom: 32 }}>
          <div
            style={{
              display: "flex",
              alignItems: "stretch",
              gap: 12,
              height: 400,
              transition: "transform 0.55s cubic-bezier(0.4,0,0.2,1)",
            }}
          >
            {CARDS.map((card, i) => {
              const isClosed = i < offset;
              return (
                <div
                  key={i}
                  onClick={() => handleCardClick(i, isClosed)}
                  style={{
                    flexBasis: isClosed ? 72 : 403,
                    flexShrink: 0,
                    flexGrow: 0,
                    background: "#1DCBF1",
                    border: `2px solid ${isClosed ? "rgba(255,255,255,0.2)" : "rgba(255,255,255,0.4)"}`,
                    borderRadius: 20,
                    overflow: "hidden",
                    display: "flex",
                    flexDirection: "column",
                    cursor: "pointer",
                    position: "relative",
                    transition: "flex-basis 0.5s cubic-bezier(0.4,0,0.2,1), border-color 0.25s",
                  }}
                >
                  {/* Badge
                      Open card:   blue rounded-square INSIDE card, top-right, white number
                      Closed strip: white arch shape centred on strip, cyan number */}
                  <div
                    style={{
                      position: "absolute",
                      top: isClosed ? 14 : 16,
                      right: isClosed ? "50%" : 16,
                      transform: isClosed ? "translateX(50%)" : "none",
                      width: isClosed ? 46 : 54,
                      height: isClosed ? 52 : 54,
                      background: isClosed ? "#fff" : "#0098ED",
                      borderRadius: isClosed ? "14px 14px 50% 50%" : "60px 20px 60px 60px",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      fontSize: isClosed ? 19 : 24,
                      fontWeight: 700,
                      color: isClosed ? "#1DCBF1" : "#fff",
                      zIndex: 4,
                      flexShrink: 0,
                      transition: "all 0.3s cubic-bezier(0.4,0,0.2,1)",
                    }}
                  >
                    {card.num}
                  </div>

                  {/* Closed strip: vertical title */}
                  {isClosed && (
                    <div
                      style={{
                        display: "flex",
                        flexDirection: "column",
                        alignItems: "center",
                        justifyContent: "flex-end",
                        padding: "16px 0 28px",
                        height: "100%",
                        overflow: "hidden",
                      }}
                    >
                      <span
                        style={{
                          writingMode: "vertical-rl",
                          transform: "rotate(180deg)",
                          fontSize: 13,
                          fontWeight: 700,
                          color: "#09202e",
                          whiteSpace: "nowrap",
                          letterSpacing: 0.3,
                        }}
                      >
                        {card.title}
                      </span>
                    </div>
                  )}

                  {/* Open card content */}
                  {!isClosed && (
                    <div
                      style={{
                        display: "flex",
                        flexDirection: "column",
                        padding: "30px 88px 28px 26px",
                        height: "100%",
                        overflow: "hidden",
                        boxSizing: "border-box",
                      }}
                    >
                      <p
                        style={{
                          fontSize: 18,
                          fontWeight: 700,
                          color: "#09202e",
                          lineHeight: 1.3,
                          marginBottom: 14,
                          margin: "0 0 14px 0",
                        }}
                      >
                        {card.title}
                      </p>
                      <div
                        style={{
                          height: 1.5,
                          background: "rgba(255,255,255,0.55)",
                          borderRadius: 2,
                          marginBottom: 16,
                          flexShrink: 0,
                        }}
                      />
                      <p
                        style={{
                          fontSize: 14,
                          color: "rgba(255,255,255,0.94)",
                          lineHeight: 1.78,
                          flex: 1,
                          overflow: "hidden",
                          margin: 0,
                        }}
                      >
                        {card.body}
                      </p>
                      <div style={{ marginTop: "auto", paddingTop: 22, flexShrink: 0 }}>
                        <a
                          href="#"
                          onClick={(e) => e.preventDefault()}
                          style={{
                            display: "inline-flex",
                            alignItems: "center",
                            gap: 7,
                            padding: "11px 26px",
                            background: "#fff",
                            borderRadius: 999,
                            fontFamily: "'Poppins', sans-serif",
                            fontSize: 14,
                            fontWeight: 600,
                            color: "#1DCBF1",
                            textDecoration: "none",
                            whiteSpace: "nowrap",
                            cursor: "pointer",
                          }}
                        >
                          Descubra más
                          <svg
                            width="13"
                            height="13"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            strokeWidth="2.2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                          >
                            <path d="M9 18l6-6-6-6" />
                          </svg>
                        </a>
                      </div>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>

        {/* Nav arrows */}
        <div style={{ display: "flex", justifyContent: "center", gap: 10 }}>
          <button
            onClick={() => setOffset((o) => Math.max(0, o - 1))}
            disabled={offset === 0}
            style={{
              width: 42,
              height: 42,
              borderRadius: "50%",
              background: "rgba(0,226,161,1)",
              border: "none",
              cursor: offset === 0 ? "default" : "pointer",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              opacity: offset === 0 ? 0.4 : 1,
              transition: "opacity 0.2s, transform 0.15s",
            }}
          >
            <svg
              width="36"
              height="36"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#fff"
              strokeWidth="2.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M15 18l-6-6 6-6" />
            </svg>
          </button>
          <button
            onClick={() => setOffset((o) => Math.min(o + 1, total - 1))}
            disabled={offset >= total - 1}
            style={{
              width: 42,
              height: 42,
              borderRadius: "50%",
              background: "rgba(0,226,161,1)",
              border: "none",
              cursor: offset >= total - 1 ? "default" : "pointer",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              opacity: offset >= total - 1 ? 0.4 : 1,
              transition: "opacity 0.2s, transform 0.15s",
            }}
          >
            <svg
              width="36"
              height="36"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#fff"
              strokeWidth="2.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M9 18l6-6-6-6" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  );
}
