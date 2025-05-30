/* document.addEventListener("mousemove", (event) => {
  const dot = document.querySelector(".cursor-dot");
  const outline = document.querySelector(".cursor-outline");

  const x = event.clientX;
  const y = event.clientY;

  if (dot && outline) {
    dot.style.left = `${x}px`;
    dot.style.top = `${y}px`;

    outline.style.left = `${x}px`;
    outline.style.top = `${y}px`;
  }
}); */

/* const dot = document.querySelector(".cursor-dot");
const outline = document.querySelector(".cursor-outline");

let outlineX = 0,
  outlineY = 0; // Position of the outer circle
let targetX = 0,
  targetY = 0; // Target position

document.addEventListener("mousemove", (event) => {
  targetX = event.clientX;
  targetY = event.clientY;

  // Instant movement for the dot (inner circle)
  if (dot) {
    dot.style.left = `${targetX}px`;
    dot.style.top = `${targetY}px`;
  }
});

// Function to animate the outer circle with delay
function animateCursor() {
  outlineX += (targetX - outlineX) * 0.3; // Smooth interpolation
  outlineY += (targetY - outlineY) * 0.3;

  if (outline) {
    outline.style.left = `${outlineX}px`;
    outline.style.top = `${outlineY}px`;
  }
  document.querySelectorAll("button, a, img").forEach((element) => {
    element.style.cursor = "none";
  });
  requestAnimationFrame(animateCursor); // Continuously animate
}

animateCursor(); // Start animation loop

 */

const dot = document.querySelector(".cursor-dot");
const outline = document.querySelector(".cursor-outline");

let outlineX = 0,
  outlineY = 0; // Position of the outer circle
let targetX = 0,
  targetY = 0; // Target position

document.addEventListener("mousemove", (event) => {
  targetX = event.clientX;
  targetY = event.clientY;

  // Instant movement for the dot (inner circle)
  if (dot) {
    dot.style.left = `${targetX}px`;
    dot.style.top = `${targetY}px`;
  }
});

// Smooth movement for the outline cursor
function animateOutline() {
  outlineX += (targetX - outlineX) * 0.3;
  outlineY += (targetY - outlineY) * 0.3;

  if (outline) {
    outline.style.left = `${outlineX}px`;
    outline.style.top = `${outlineY}px`;
  }

  requestAnimationFrame(animateOutline);
}

animateOutline(); // Start the animation loop
