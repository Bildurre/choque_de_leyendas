export function setupLifePointsCalculator() {
  const attributeInputs = [
    document.querySelector('input[name="base_agility"]'),
    document.querySelector('input[name="base_mental"]'),
    document.querySelector('input[name="base_will"]'),
    document.querySelector('input[name="base_strength"]'),
    document.querySelector('input[name="base_armor"]')
  ];
  
  const totalPointsInput = document.querySelector('input[name="total_points"]');
  const lifePointsInput = document.querySelector('input[name="base_life"]');
  
  if (!attributeInputs.every(input => input) || !totalPointsInput || !lifePointsInput) {
    console.log("Error: No se pudieron encontrar todos los campos necesarios");
    return;
  }
  
  function updateLifePoints() {
    const totalPoints = parseInt(totalPointsInput.value) || 0;
    
    let attributesSum = 0;
    attributeInputs.forEach(input => {
      attributesSum += parseInt(input.value) || 0;
    });
  
    const lifePoints = totalPoints - attributesSum;
    
    lifePointsInput.value = lifePoints;
  }
  
  attributeInputs.forEach(input => {
    input.addEventListener('input', updateLifePoints);
  });
  
  totalPointsInput.addEventListener('input', updateLifePoints);
  
  updateLifePoints();
}