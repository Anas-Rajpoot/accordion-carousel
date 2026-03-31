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

export function MobileCarousel() {
  // card 0 starts closed, rest open
  const [closed, setClosed] = useState<Set<number>>(new Set([0]));

  const toggle = (i: number) => {
    setClosed((prev) => {
      const next = new Set(prev);
      if (next.has(i)) next.delete(i);
      else next.add(i);
      return next;
    });
  };

  return (
    <div
      style={{
        fontFamily: "'Poppins', sans-serif",
        background: "#0098ED",
        padding: "28px 0 32px",
        minHeight: "100vh",
      }}
    >
      <div style={{ padding: "0 16px" }}>
        <div style={{ display: "flex", flexDirection: "column", gap: 10 }}>
          {CARDS.map((card, i) => {
            const isClosed = closed.has(i);
            return (
              <div
                key={i}
                onClick={() => toggle(i)}
                style={{
                  width: "100%",
                  background: "#1DCBF1",
                  border: `2px solid ${isClosed ? "rgba(255,255,255,0.2)" : "rgba(255,255,255,0.35)"}`,
                  borderRadius: 18,
                  overflow: "visible",
                  cursor: "pointer",
                  position: "relative",
                  boxSizing: "border-box",
                }}
              >
                {/* Mobile card header — always visible */}
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "space-between",
                    padding: "18px 18px 16px",
                    gap: 10,
                  }}
                >
                  <span
                    style={{
                      fontSize: 15,
                      fontWeight: 700,
                      color: "#09202e",
                      flex: 1,
                      lineHeight: 1.3,
                    }}
                  >
                    {card.title}
                  </span>
                  <div
                    style={{
                      width: 44,
                      height: 44,
                      background: isClosed ? "#fff" : "#0098ED",
                      /* open:  60 20 60 60 — matches desktop open badge */
                      /* closed: 60 20 20 60 — Figma closed mobile badge */
                      borderRadius: isClosed ? "60px 20px 20px 60px" : "60px 20px 60px 60px",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      fontSize: 17,
                      fontWeight: 700,
                      color: isClosed ? "#1DCBF1" : "#fff",
                      flexShrink: 0,
                      transition: "border-radius 0.3s cubic-bezier(0.4,0,0.2,1), background 0.25s",
                    }}
                  >
                    {card.num}
                  </div>
                </div>

                {/* Open card body */}
                {!isClosed && (
                  <div
                    style={{
                      display: "flex",
                      flexDirection: "column",
                      padding: "0 18px 20px",
                    }}
                  >
                    {/* Divider */}
                    <div
                      style={{
                        height: 1.5,
                        background: "rgba(255,255,255,0.55)",
                        borderRadius: 2,
                        marginBottom: 14,
                        flexShrink: 0,
                      }}
                    />
                    {/* Body */}
                    <p
                      style={{
                        fontSize: 14,
                        color: "rgba(255,255,255,0.94)",
                        lineHeight: 1.78,
                        margin: 0,
                      }}
                    >
                      {card.body}
                    </p>
                    {/* Button — full width */}
                    <div style={{ paddingTop: 18, width: "100%" }}>
                      <a
                        href="#"
                        onClick={(e) => e.preventDefault()}
                        style={{
                          display: "flex",
                          width: "100%",
                          justifyContent: "center",
                          alignItems: "center",
                          gap: 7,
                          padding: "13px 26px",
                          background: "#fff",
                          borderRadius: 999,
                          fontFamily: "'Poppins', sans-serif",
                          fontSize: 14,
                          fontWeight: 600,
                          color: "#1DCBF1",
                          textDecoration: "none",
                          boxSizing: "border-box",
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
    </div>
  );
}
