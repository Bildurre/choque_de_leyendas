<section>
  <table class="dice-values">
    <tr>
      <th>1 dado</th>
      <th>2 dados</th>
      <th>3 dados</th>
    </tr>
    <tr>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" />,&nbsp;</span>
            <span><x-icon-dice type="green" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">86-96%</span>
            <span class="dice-values__value">2</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">81-83%</span>
            <span class="dice-values__value">3</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /><x-icon-dice type="green" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">68%</span>
            <span class="dice-values__value">5</span>
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">59%</span>
            <span class="dice-values__value">3</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">49-57%</span>
            <span class="dice-values__value">4</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="green" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /><x-icon-dice type="red" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="green" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">44-50%</span>
            <span class="dice-values__value">6</span>
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <td></td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="blue" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">19%</span>
            <span class="dice-values__value">5</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="green" /><x-icon-dice type="green" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="green" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="blue" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="blue" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">14-27%</span>
            <span class="dice-values__value">7</span>
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <td></td>
      <td></td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="blue" /><x-icon-dice type="blue" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">3%</span>
            <span class="dice-values__value">8</span>
          </span>
        </div>
      </td>
    </tr>
  </table>

  <table class="effect-values">
    <tr>
      <th colspan="2">Efecto único</th>
      <th colspan="2">Efecto permanente</th>
    </tr>
    <tr>
      <td>Aumento de daño</td>
      <td class="effect-values__value">Base</td>
      <td>Aumento de daño</td>
      <td class="effect-values__value">Base * 2</td>
    </tr>
    <tr>
      <td>Reduccion de daño</td>
      <td class="effect-values__value">Base</td>
      <td>Reduccion de daño</td>
      <td class="effect-values__value">Base * 2</td>
    </tr>
    <tr>
      <td>Contador</td>
      <td class="effect-values__value">Base (exaltado-exhausto e inspirado-aterrado 2, resto 1)</td>
      <td>Contador por golpe</td>
      <td class="effect-values__value">Base * 2 (exaltado-exhausto e inspirado-aterrado 2, resto 1)</td>
    </tr>
    <tr>
      <td>Efecto versatil</td>
      <td class="effect-values__value">+1</td>
      <td>Contador en 1 golpe por combate</td>
      <td class="effect-values__value">Base * 1.5 (exaltado-exhausto e inspirado-aterrado 2, resto 1)</td>
    </tr>
    <tr>
      <td>Aumento de daño todo el combate o ronda</td>
      <td class="effect-values__value">Base * 1.5</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Reducción de daño todo el combate o ronda</td>
      <td class="effect-values__value">Base * 1.5</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Roba 1</td>
      <td class="effect-values__value">+2</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Cicla 1</td>
      <td class="effect-values__value">+1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Ardid (o activa como ardid)</td>
      <td class="effect-values__value">+1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Restricción (clase,...)</td>
      <td class="effect-values__value">-1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Carta Única</td>
      <td class="effect-values__value">-1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>Efecto de Área</td>
      <td class="effect-values__value">+1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
  </table>
</section>