import React from 'react';
import { useState } from 'react';

const HeroCardVariations = () => {
  const [activeVariant, setActiveVariant] = useState('variant1');
  
  // Common card dimensions (based on MTG/Pokemon proportions)
  const cardWidth = 320;
  const cardHeight = 445;
  const borderRadius = 12;
  
  // Sample hero data
  const heroData = {
    name: "Lyria Stormwind",
    race: "Elemental",
    heroClass: "Mage",
    superclass: "Caster",
    faction: {
      name: "Wind Keepers",
      color: "#3498db",
      secondaryColor: "#2980b9"
    },
    attributes: {
      strength: 2,
      agility: 3,
      armor: 2,
      will: 4,
      mental: 5
    },
    health: 16,
    passives: [
      {
        name: "Arcane Insight",
        description: "Whenever you cast a spell, gain 1 Inspired counter."
      },
      {
        name: "Elemental Attunement",
        description: "Your first spell each turn costs 1 less Resource."
      }
    ],
    actives: [
      {
        name: "Energy Bolt",
        cost: "BB",
        range: "Ranged",
        type: "Attack",
        subtype: "Spell",
        description: "Deal 3 damage to a hero. If you have at least 2 Inspired counters, deal 5 damage instead."
      },
      {
        name: "Aether Shield",
        cost: "GG",
        range: "Self",
        type: "Defense",
        subtype: "Boost",
        description: "Gain 2 Armor until your next turn. Draw a card."
      }
    ]
  };
  
  // Attribute icons
  const attributeIcons = {
    strength: "💪",
    agility: "🏃",
    armor: "🛡️",
    will: "✨",
    mental: "🧠",
    health: "❤️"
  };
  
  // Variant 1: Extended text area with icons
  const Variant1 = () => (
    <div 
      style={{
        width: cardWidth,
        height: cardHeight,
        borderRadius: borderRadius,
        position: 'relative',
        overflow: 'hidden',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.3)',
        fontFamily: 'roboto, sans-serif'
      }}
    >
      {/* Background Image */}
      <div 
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          backgroundImage: 'url("/api/placeholder/320/445")',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          filter: 'brightness(0.7)',
          zIndex: 1
        }}
      />
      
      {/* Card Content Container */}
      <div 
        style={{
          position: 'relative',
          width: '100%',
          height: '100%',
          zIndex: 2,
          display: 'flex',
          flexDirection: 'column'
        }}
      >
        {/* Header Section with Diagonal Cut */}
        <div 
          style={{
            position: 'relative',
            height: '65px',
            background: `linear-gradient(135deg, ${heroData.faction.color} 0%, ${heroData.faction.secondaryColor} 100%)`,
            clipPath: 'polygon(0 0, 100% 0, 100% 70%, 0 100%)',
            padding: '10px 15px',
            display: 'flex',
            alignItems: 'flex-start',
            justifyContent: 'space-between'
          }}
        >
          {/* Hero Name */}
          <div>
            <h2 style={{ 
              margin: 0, 
              fontSize: '18px', 
              fontWeight: 'bold',
              color: 'white',
              textShadow: '0 1px 2px rgba(0,0,0,0.5)'
            }}>
              {heroData.name}
            </h2>
            <div style={{ 
              fontSize: '12px', 
              color: 'rgba(255,255,255,0.9)',
              marginTop: '3px'
            }}>
              {heroData.race} • {heroData.heroClass} • {heroData.superclass}
            </div>
          </div>
          
          {/* Faction Symbol */}
          <div style={{
            width: '32px',
            height: '32px',
            borderRadius: '50%',
            backgroundColor: 'white',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            fontSize: '16px',
            fontWeight: 'bold',
            color: heroData.faction.color,
            boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
          }}>
            {heroData.faction.name.charAt(0)}
          </div>
        </div>
        
        {/* Attributes Section with Diagonal Cut */}
        <div style={{
          marginTop: 'auto',
          position: 'relative',
          padding: '15px',
          background: 'rgba(0, 0, 0, 0.75)',
          clipPath: 'polygon(0 25%, 100% 0, 100% 100%, 0 100%)',
          minHeight: '260px',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'flex-end'
        }}>
          {/* Attributes Display */}
          <div style={{
            display: 'flex',
            flexDirection: 'row',
            justifyContent: 'space-between',
            marginBottom: '12px',
            marginTop: '15px'
          }}>
            {Object.entries(heroData.attributes).map(([attr, value]) => (
              <div key={attr} style={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center'
              }}>
                <div style={{
                  width: '36px',
                  height: '36px',
                  borderRadius: '50%',
                  backgroundColor: 'rgba(255, 255, 255, 0.15)',
                  border: `2px solid ${heroData.faction.color}`,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '18px',
                  fontWeight: 'bold',
                  color: 'white'
                }}>
                  <span style={{ marginRight: '2px' }}>{attributeIcons[attr]}</span> {value}
                </div>
                <div style={{
                  fontSize: '10px',
                  color: 'rgba(255, 255, 255, 0.8)',
                  marginTop: '4px',
                  textTransform: 'uppercase',
                  letterSpacing: '0.5px'
                }}>
                  {attr}
                </div>
              </div>
            ))}
            
            <div style={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center'
            }}>
              <div style={{
                width: '42px',
                height: '42px',
                borderRadius: '50%',
                backgroundColor: 'rgba(255, 20, 20, 0.2)',
                border: '2px solid #e74c3c',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: '20px',
                fontWeight: 'bold',
                color: '#e74c3c'
              }}>
                <span style={{ marginRight: '2px' }}>{attributeIcons.health}</span> {heroData.health}
              </div>
              <div style={{
                fontSize: '10px',
                color: 'rgba(255, 255, 255, 0.8)',
                marginTop: '4px',
                textTransform: 'uppercase',
                letterSpacing: '0.5px'
              }}>
                HEALTH
              </div>
            </div>
          </div>
          
          {/* Abilities Container */}
          <div style={{ 
            backgroundColor: 'rgba(0, 0, 0, 0.6)',
            borderRadius: '8px',
            marginTop: '10px',
            maxHeight: '180px',
            overflow: 'auto'
          }}>
            {/* Passive Abilities */}
            {heroData.passives.map((passive, index) => (
              <div key={`passive-${index}`} style={{
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                padding: '8px 10px',
                borderRadius: '6px',
                marginBottom: '5px',
                border: `1px solid ${heroData.faction.color}`
              }}>
                <div style={{
                  fontSize: '11px',
                  fontWeight: 'bold',
                  color: heroData.faction.color,
                  marginBottom: '2px',
                  display: 'flex',
                  alignItems: 'center'
                }}>
                  <span style={{ marginRight: '5px', fontSize: '10px' }}>⭐</span>
                  PASSIVE: {passive.name}
                </div>
                <div style={{
                  fontSize: '11px',
                  color: 'white',
                  lineHeight: '1.3'
                }}>
                  {passive.description}
                </div>
              </div>
            ))}
            
            {/* Active Abilities */}
            {heroData.actives.map((active, index) => (
              <div key={`active-${index}`} style={{
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                padding: '8px 10px',
                borderRadius: '6px',
                marginBottom: '5px',
                border: `1px solid ${heroData.faction.color}`
              }}>
                <div style={{
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center',
                  marginBottom: '2px'
                }}>
                  <div style={{
                    fontSize: '11px',
                    fontWeight: 'bold',
                    color: heroData.faction.color,
                    display: 'flex',
                    alignItems: 'center'
                  }}>
                    <span style={{ marginRight: '5px', fontSize: '10px' }}>⚡</span>
                    {active.name}
                  </div>
                  <div style={{
                    fontSize: '10px',
                    color: 'white',
                    backgroundColor: heroData.faction.color,
                    padding: '2px 4px',
                    borderRadius: '4px'
                  }}>
                    {active.cost}
                  </div>
                </div>
                <div style={{
                  fontSize: '9px',
                  color: 'rgba(255, 255, 255, 0.7)',
                  marginBottom: '3px'
                }}>
                  {active.range} • {active.type} • {active.subtype}
                </div>
                <div style={{
                  fontSize: '11px',
                  color: 'white',
                  lineHeight: '1.3'
                }}>
                  {active.description}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
  
  // Variant 2: Dark elegant with accent line
  const Variant2 = () => (
    <div 
      style={{
        width: cardWidth,
        height: cardHeight,
        borderRadius: borderRadius,
        position: 'relative',
        overflow: 'hidden',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.3)',
        fontFamily: 'roboto, sans-serif',
        border: `1px solid ${heroData.faction.color}`
      }}
    >
      {/* Background Image */}
      <div 
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          backgroundImage: 'url("/api/placeholder/320/445")',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          filter: 'brightness(0.6)',
          zIndex: 1
        }}
      />
      
      {/* Card Content Container */}
      <div 
        style={{
          position: 'relative',
          width: '100%',
          height: '100%',
          zIndex: 2,
          display: 'flex',
          flexDirection: 'column'
        }}
      >
        {/* Left accent bar */}
        <div style={{
          position: 'absolute',
          left: 0,
          top: 0,
          width: '5px',
          height: '100%',
          background: `linear-gradient(to bottom, ${heroData.faction.color}, ${heroData.faction.secondaryColor})`,
          zIndex: 3
        }} />
        
        {/* Header */}
        <div 
          style={{
            position: 'relative',
            padding: '12px 15px',
            display: 'flex',
            alignItems: 'flex-start',
            justifyContent: 'space-between',
            borderBottom: `1px solid ${heroData.faction.color}`
          }}
        >
          {/* Hero Name */}
          <div>
            <h2 style={{ 
              margin: 0, 
              fontSize: '18px', 
              fontWeight: 'bold',
              color: 'white',
              textShadow: '0 1px 2px rgba(0,0,0,0.5)'
            }}>
              {heroData.name}
            </h2>
            <div style={{ 
              fontSize: '12px', 
              color: 'rgba(255,255,255,0.9)',
              marginTop: '3px'
            }}>
              {heroData.race} • {heroData.heroClass} • {heroData.superclass}
            </div>
          </div>
          
          {/* Faction Symbol */}
          <div style={{
            width: '36px',
            height: '36px',
            borderRadius: '50%',
            backgroundColor: 'rgba(0,0,0,0.5)',
            border: `2px solid ${heroData.faction.color}`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            fontSize: '16px',
            fontWeight: 'bold',
            color: heroData.faction.color,
            boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
          }}>
            {heroData.faction.name.charAt(0)}
          </div>
        </div>
        
        {/* Attributes Section */}
        <div style={{
          marginTop: 'auto',
          position: 'relative',
          padding: '12px 15px',
          background: 'rgba(0, 0, 0, 0.75)',
          backdropFilter: 'blur(3px)',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'flex-end',
          borderTop: `1px solid ${heroData.faction.color}`
        }}>
          {/* Attributes Display */}
          <div style={{
            display: 'flex',
            flexDirection: 'row',
            justifyContent: 'space-between',
            marginBottom: '10px'
          }}>
            {Object.entries(heroData.attributes).map(([attr, value]) => (
              <div key={attr} style={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center'
              }}>
                <div style={{
                  width: '36px',
                  height: '36px',
                  borderRadius: '4px',
                  backgroundColor: 'rgba(255, 255, 255, 0.1)',
                  border: `1px solid ${heroData.faction.color}`,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '18px',
                  color: 'white'
                }}>
                  <div style={{ fontSize: '14px', marginRight: '2px' }}>{attributeIcons[attr]}</div>
                  <div style={{ fontWeight: 'bold' }}>{value}</div>
                </div>
                <div style={{
                  fontSize: '9px',
                  color: 'rgba(255, 255, 255, 0.8)',
                  marginTop: '3px',
                  textTransform: 'uppercase',
                  letterSpacing: '0.5px'
                }}>
                  {attr}
                </div>
              </div>
            ))}
            
            <div style={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center'
            }}>
              <div style={{
                width: '40px',
                height: '40px',
                borderRadius: '4px',
                backgroundColor: 'rgba(231, 76, 60, 0.2)',
                border: '1px solid #e74c3c',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: '18px',
                color: '#e74c3c'
              }}>
                <div style={{ fontSize: '14px', marginRight: '2px' }}>{attributeIcons.health}</div>
                <div style={{ fontWeight: 'bold' }}>{heroData.health}</div>
              </div>
              <div style={{
                fontSize: '9px',
                color: 'rgba(255, 255, 255, 0.8)',
                marginTop: '3px',
                textTransform: 'uppercase',
                letterSpacing: '0.5px'
              }}>
                HEALTH
              </div>
            </div>
          </div>
          
          {/* Abilities Container */}
          <div style={{ 
            borderRadius: '6px',
            marginTop: '5px',
            maxHeight: '200px',
            overflow: 'auto'
          }}>
            {/* Passives heading */}
            <div style={{
              fontSize: '11px',
              textTransform: 'uppercase',
              color: heroData.faction.color,
              fontWeight: 'bold',
              letterSpacing: '1px',
              marginBottom: '5px',
              display: 'flex',
              alignItems: 'center'
            }}>
              <div style={{
                width: '10px',
                height: '10px',
                backgroundColor: heroData.faction.color,
                marginRight: '5px',
                clipPath: 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)'
              }}></div>
              Passive Abilities
            </div>
            
            {/* Passive Abilities */}
            <div style={{
              marginBottom: '8px'
            }}>
              {heroData.passives.map((passive, index) => (
                <div key={`passive-${index}`} style={{
                  backgroundColor: 'rgba(0, 0, 0, 0.5)',
                  padding: '6px 8px',
                  borderRadius: '4px',
                  marginBottom: '5px',
                  borderLeft: `3px solid ${heroData.faction.color}`
                }}>
                  <div style={{
                    fontSize: '10px',
                    fontWeight: 'bold',
                    color: 'white',
                    marginBottom: '2px'
                  }}>
                    {passive.name}
                  </div>
                  <div style={{
                    fontSize: '10px',
                    color: 'white',
                    lineHeight: '1.2'
                  }}>
                    {passive.description}
                  </div>
                </div>
              ))}
            </div>
            
            {/* Actives heading */}
            <div style={{
              fontSize: '11px',
              textTransform: 'uppercase',
              color: heroData.faction.color,
              fontWeight: 'bold',
              letterSpacing: '1px',
              marginBottom: '5px',
              display: 'flex',
              alignItems: 'center'
            }}>
              <div style={{
                width: '10px',
                height: '10px',
                backgroundColor: heroData.faction.color,
                marginRight: '5px',
                clipPath: 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)'
              }}></div>
              Active Abilities
            </div>
            
            {/* Active Abilities */}
            {heroData.actives.map((active, index) => (
              <div key={`active-${index}`} style={{
                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                padding: '6px 8px',
                borderRadius: '4px',
                marginBottom: '5px',
                borderLeft: `3px solid ${heroData.faction.color}`
              }}>
                <div style={{
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center',
                  marginBottom: '2px'
                }}>
                  <div style={{
                    fontSize: '10px',
                    fontWeight: 'bold',
                    color: 'white'
                  }}>
                    {active.name}
                  </div>
                  <div style={{
                    fontSize: '10px',
                    color: 'white',
                    backgroundColor: heroData.faction.color,
                    padding: '1px 4px',
                    borderRadius: '3px'
                  }}>
                    {active.cost}
                  </div>
                </div>
                <div style={{
                  fontSize: '8px',
                  color: 'rgba(255, 255, 255, 0.8)',
                  marginBottom: '2px',
                  fontStyle: 'italic'
                }}>
                  {active.range} • {active.type} • {active.subtype}
                </div>
                <div style={{
                  fontSize: '10px',
                  color: 'white',
                  lineHeight: '1.2'
                }}>
                  {active.description}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
  
  // Variant 3: Tabbed design
  const Variant3 = () => {
    // Simulation of a selected tab
    const [selectedTab, setSelectedTab] = useState('passives');
    
    return (
      <div 
        style={{
          width: cardWidth,
          height: cardHeight,
          borderRadius: borderRadius,
          position: 'relative',
          overflow: 'hidden',
          boxShadow: '0 10px 25px rgba(0, 0, 0, 0.3)',
          fontFamily: 'roboto, sans-serif'
        }}
      >
        {/* Background Image */}
        <div 
          style={{
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            backgroundImage: 'url("/api/placeholder/320/445")',
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            filter: 'brightness(0.65)',
            zIndex: 1
          }}
        />
        
        {/* Card Content Container */}
        <div 
          style={{
            position: 'relative',
            width: '100%',
            height: '100%',
            zIndex: 2,
            display: 'flex',
            flexDirection: 'column'
          }}
        >
          {/* Header Section with Diagonal Cut */}
          <div 
            style={{
              position: 'relative',
              height: '65px',
              background: `linear-gradient(135deg, ${heroData.faction.color} 0%, ${heroData.faction.secondaryColor} 100%)`,
              clipPath: 'polygon(0 0, 100% 0, 100% 70%, 0 100%)',
              padding: '10px 15px',
              display: 'flex',
              alignItems: 'flex-start',
              justifyContent: 'space-between'
            }}
          >
            {/* Hero Name */}
            <div>
              <h2 style={{ 
                margin: 0, 
                fontSize: '18px', 
                fontWeight: 'bold',
                color: 'white',
                textShadow: '0 1px 2px rgba(0,0,0,0.5)'
              }}>
                {heroData.name}
              </h2>
              <div style={{ 
                fontSize: '12px', 
                color: 'rgba(255,255,255,0.9)',
                marginTop: '3px'
              }}>
                {heroData.race} • {heroData.heroClass} • {heroData.superclass}
              </div>
            </div>
            
            {/* Faction Symbol */}
            <div style={{
              width: '32px',
              height: '32px',
              borderRadius: '50%',
              backgroundColor: 'white',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontSize: '16px',
              fontWeight: 'bold',
              color: heroData.faction.color,
              boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
            }}>
              {heroData.faction.name.charAt(0)}
            </div>
          </div>
          
          {/* Main Content Area */}
          <div style={{ 
            flex: 1,
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'flex-end',
            padding: '0 10px'
          }}>
            {/* Attributes Bar */}
            <div style={{
              display: 'flex',
              justifyContent: 'space-between',
              backgroundColor: 'rgba(0, 0, 0, 0.7)',
              borderRadius: '8px',
              padding: '10px',
              marginBottom: '10px'
            }}>
              {Object.entries(heroData.attributes).map(([attr, value]) => (
                <div key={attr} style={{
                  display: 'flex',
                  flexDirection: 'column',
                  alignItems: 'center'
                }}>
                  <div style={{
                    width: '30px',
                    height: '30px',
                    borderRadius: '50%',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)',
                    border: `1px solid ${heroData.faction.color}`,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    color: 'white'
                  }}>
                    <div style={{ fontSize: '12px', marginRight: '1px' }}>{attributeIcons[attr]}</div>
                    <div style={{ fontSize: '14px', fontWeight: 'bold' }}>{value}</div>
                  </div>
                  <div style={{
                    fontSize: '8px',
                    color: 'rgba(255, 255, 255, 0.8)',
                    marginTop: '2px',
                    textTransform: 'uppercase'
                  }}>
                    {attr}
                  </div>
                </div>
              ))}
              
              <div style={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center'
              }}>
                <div style={{
                  width: '34px',
                  height: '34px',
                  borderRadius: '50%',
                  backgroundColor: 'rgba(231, 76, 60, 0.2)',
                  border: '1px solid #e74c3c',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: '#e74c3c'
                }}>
                  <div style={{ fontSize: '12px', marginRight: '1px' }}>{attributeIcons.health}</div>
                  <div style={{ fontSize: '16px', fontWeight: 'bold' }}>{heroData.health}</div>
                </div>
                <div style={{
                  fontSize: '8px',
                  color: 'rgba(255, 255, 255, 0.8)',
                  marginTop: '2px',
                  textTransform: 'uppercase'
                }}>
                  HP
                </div>
              </div>
            </div>
          </div>
          
          {/* Tab Section */}
          <div style={{
            backgroundColor: 'rgba(0, 0, 0, 0.85)',
            padding: '12px 15px 15px',
            borderTopLeftRadius: '15px',
            borderTopRightRadius: '15px'
          }}>
            {/* Tabs */}
            <div style={{
              display: 'flex',
              marginBottom: '10px'
            }}>
              <div 
                style={{
                  padding: '5px 12px',
                  backgroundColor: selectedTab === 'passives' ? heroData.faction.color : 'rgba(255, 255, 255, 0.1)',
                  borderRadius: '5px',
                  marginRight: '8px',
                  fontSize: '11px',
                  fontWeight: selectedTab === 'passives' ? 'bold' : 'normal',
                  color: selectedTab === 'passives' ? 'white' : 'rgba(255, 255, 255, 0.7)',
                  cursor: 'pointer'
                }}
                onClick={() => setSelectedTab('passives')}
              >
                Passive
              </div>
              <div 
                style={{
                  padding: '5px 12px',
                  backgroundColor: selectedTab === 'actives' ? heroData.faction.color : 'rgba(255, 255, 255, 0.1)',
                  borderRadius: '5px',
                  fontSize: '11px',
                  fontWeight: selectedTab === 'actives' ? 'bold' : 'normal',
                  color: selectedTab === 'actives' ? 'white' : 'rgba(255, 255, 255, 0.7)',
                  cursor: 'pointer'
                }}
                onClick={() => setSelectedTab('actives')}
              >
                Active
              </div>
            </div>
            
            {/* Tab Content */}
            <div style={{
              minHeight: '150px',
              maxHeight: '150px',
              overflow: 'auto'
            }}>
              {selectedTab === 'passives' && (
                <div>
                  {heroData.passives.map((passive, index) => (
                    <div key={`passive-${index}`} style={{
                      backgroundColor: 'rgba(255, 255, 255, 0.05)',
                      padding: '8px 10px',
                      borderRadius: '6px',
                      marginBottom: '8px',
                      borderLeft: `3px solid ${heroData.faction.color}`
                    }}>
                      <div style={{
                        fontSize: '12px',
                        fontWeight: 'bold',
                        color: heroData.faction.color,
                        marginBottom: '5px',
                        display: 'flex',
                        alignItems: 'center'
                      }}>
                        <span style={{ marginRight: '5px', fontSize: '10px' }}>⭐</span>
                        {passive.name}
                      </div>
                      <div style={{
                        fontSize: '11px',
                        color: 'white',
                        lineHeight: '1.4'
                      }}>
                        {passive.description}
                      </div>
                    </div>
                  ))}
                </div>
              )}
              
              {selectedTab === 'actives' && (
                <div>
                  {heroData.actives.map((active, index) => (
                    <div key={`active-${index}`} style={{
                      backgroundColor: 'rgba(255, 255, 255, 0.05)',
                      padding: '8px 10px',
                      borderRadius: '6px',
                      marginBottom: '8px',
                      borderLeft: `3px solid ${heroData.faction.color}`
                    }}>
                      <div style={{
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        marginBottom: '4px'
                      }}>
                        <div style={{
                          fontSize: '12px',
                          fontWeight: 'bold',
                          color: heroData.faction.color,
                          display: 'flex',
                          alignItems: 'center'
                        }}>
                          <span style={{ marginRight: '5px', fontSize: '10px' }}>⚡</span>
                          {active.name}
                        </div>
                        <div style={{
                          fontSize: '11px',
                          color: 'white',
                          backgroundColor: heroData.faction.color,
                          padding: '2px 6px',
                          borderRadius: '4px'
                        }}>
                          {active.cost}
                        </div>
                      </div>
                      <div style={{
                        fontSize: '10px',
                        color: 'rgba(255, 255, 255, 0.7)',
                        marginBottom: '5px'
                      }}>
                        {active.range} • {active.type} • {active.subtype}
                      </div>
                      <div style={{
                        fontSize: '11px',
                        color: 'white',
                        lineHeight: '1.4'
                      }}>
                        {active.description}
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    );
  };
  
  // Variant 4: Full card with accent color and expanded text area
  const Variant4 = () => (
    <div 
      style={{
        width: cardWidth,
        height: cardHeight,
        borderRadius: borderRadius,
        position: 'relative',
        overflow: 'hidden',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.3)',
        fontFamily: 'roboto, sans-serif',
        border: `2px solid ${heroData.faction.color}`
      }}
    >
      {/* Background Image with gradient overlay */}
      <div 
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          backgroundImage: 'url("/api/placeholder/320/445")',
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          zIndex: 1
        }}
      />
      
      {/* Gradient overlay */}
      <div 
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          background: `linear-gradient(to bottom, 
            rgba(0,0,0,0.3) 0%, 
            rgba(0,0,0,0.5) 40%, 
            rgba(0,0,0,0.85) 70%, 
            rgba(0,0,0,0.95) 100%)`,
          zIndex: 2
        }}
      />
      
      {/* Card Content Container */}
      <div 
        style={{
          position: 'relative',
          width: '100%',
          height: '100%',
          zIndex: 3,
          display: 'flex',
          flexDirection: 'column'
        }}
      >
        {/* Colored top bar */}
        <div style={{
          height: '8px',
          backgroundColor: heroData.faction.color,
          width: '100%'
        }}></div>
        
        {/* Header */}
        <div style={{
          padding: '12px 15px',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'flex-start'
        }}>
          <div>
            <h2 style={{ 
              margin: 0, 
              fontSize: '20px', 
              fontWeight: 'bold',
              color: 'white',
              textShadow: '0 2px 4px rgba(0,0,0,0.5)'
            }}>
              {heroData.name}
            </h2>
            <div style={{ 
              fontSize: '12px', 
              color: 'rgba(255,255,255,0.9)',
              marginTop: '4px',
              textShadow: '0 1px 2px rgba(0,0,0,0.7)'
            }}>
              {heroData.race} • {heroData.heroClass} • {heroData.superclass}
            </div>
          </div>
          
          {/* Faction Badge */}
          <div style={{
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            backgroundColor: heroData.faction.color,
            color: 'white',
            padding: '4px 8px',
            borderRadius: '4px',
            fontSize: '12px',
            fontWeight: 'bold',
            boxShadow: '0 2px 4px rgba(0,0,0,0.3)'
          }}>
            {heroData.faction.name}
          </div>
        </div>
        
        {/* Spacer */}
        <div style={{ flex: 1 }}></div>
        
        {/* Attributes Bar */}
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          padding: '8px 15px',
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          borderTop: `1px solid ${heroData.faction.color}`,
          borderBottom: `1px solid ${heroData.faction.color}`
        }}>
          {Object.entries(heroData.attributes).map(([attr, value]) => (
            <div key={attr} style={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center'
            }}>
              <div style={{
                width: '32px',
                height: '32px',
                borderRadius: '4px',
                backgroundColor: 'rgba(255, 255, 255, 0.1)',
                border: `1px solid ${heroData.faction.color}`,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: 'white'
              }}>
                <div style={{ fontSize: '13px', marginRight: '1px' }}>{attributeIcons[attr]}</div>
                <div style={{ fontSize: '15px', fontWeight: 'bold' }}>{value}</div>
              </div>
              <div style={{
                fontSize: '9px',
                color: 'rgba(255, 255, 255, 0.8)',
                marginTop: '3px',
                textTransform: 'uppercase'
              }}>
                {attr}
              </div>
            </div>
          ))}
          
          <div style={{
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center'
          }}>
            <div style={{
              width: '38px',
              height: '38px',
              borderRadius: '4px',
              backgroundColor: 'rgba(231, 76, 60, 0.2)',
              border: '1px solid #e74c3c',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#e74c3c'
            }}>
              <div style={{ fontSize: '13px', marginRight: '1px' }}>{attributeIcons.health}</div>
              <div style={{ fontSize: '18px', fontWeight: 'bold' }}>{heroData.health}</div>
            </div>
            <div style={{
              fontSize: '9px',
              color: 'rgba(255, 255, 255, 0.8)',
              marginTop: '3px',
              textTransform: 'uppercase'
            }}>
              Health
            </div>
          </div>
        </div>
        
        {/* Abilities Container */}
        <div style={{
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: '12px 15px',
          maxHeight: '190px',
          overflow: 'auto'
        }}>
          {/* Two columns for abilities */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: '1fr 1fr',
            gap: '10px'
          }}>
            {/* Left Column: Passives */}
            <div>
              <div style={{
                fontSize: '11px',
                fontWeight: 'bold',
                color: heroData.faction.color,
                textTransform: 'uppercase',
                marginBottom: '6px',
                textAlign: 'center',
                borderBottom: `1px solid ${heroData.faction.color}`,
                paddingBottom: '3px'
              }}>
                Passive Abilities
              </div>
              
              {heroData.passives.map((passive, index) => (
                <div key={`passive-${index}`} style={{
                  backgroundColor: 'rgba(255, 255, 255, 0.05)',
                  padding: '6px 8px',
                  borderRadius: '4px',
                  marginBottom: '6px',
                  border: `1px solid rgba(255, 255, 255, 0.1)`
                }}>
                  <div style={{
                    fontSize: '10px',
                    fontWeight: 'bold',
                    color: heroData.faction.color,
                    marginBottom: '3px'
                  }}>
                    {passive.name}
                  </div>
                  <div style={{
                    fontSize: '9px',
                    color: 'white',
                    lineHeight: '1.3'
                  }}>
                    {passive.description}
                  </div>
                </div>
              ))}
            </div>
            
            {/* Right Column: Actives */}
            <div>
              <div style={{
                fontSize: '11px',
                fontWeight: 'bold',
                color: heroData.faction.color,
                textTransform: 'uppercase',
                marginBottom: '6px',
                textAlign: 'center',
                borderBottom: `1px solid ${heroData.faction.color}`,
                paddingBottom: '3px'
              }}>
                Active Abilities
              </div>
              
              {heroData.actives.map((active, index) => (
                <div key={`active-${index}`} style={{
                  backgroundColor: 'rgba(255, 255, 255, 0.05)',
                  padding: '6px 8px',
                  borderRadius: '4px',
                  marginBottom: '6px',
                  border: `1px solid rgba(255, 255, 255, 0.1)`
                }}>
                  <div style={{
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    marginBottom: '2px'
                  }}>
                    <div style={{
                      fontSize: '10px',
                      fontWeight: 'bold',
                      color: heroData.faction.color
                    }}>
                      {active.name}
                    </div>
                    <div style={{
                      fontSize: '9px',
                      color: 'white',
                      backgroundColor: heroData.faction.color,
                      padding: '1px 3px',
                      borderRadius: '2px'
                    }}>
                      {active.cost}
                    </div>
                  </div>
                  <div style={{
                    fontSize: '7px',
                    color: 'rgba(255, 255, 255, 0.7)',
                    marginBottom: '2px'
                  }}>
                    {active.range} • {active.type} • {active.subtype}
                  </div>
                  <div style={{
                    fontSize: '9px',
                    color: 'white',
                    lineHeight: '1.3'
                  }}>
                    {active.description}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );

  return (
    <div style={{ fontFamily: 'system-ui, sans-serif' }}>
      <div style={{ marginBottom: '20px' }}>
        <h2 style={{ marginBottom: '10px' }}>Hero Card Design Variations</h2>
        <p>Here are four variations of the hero card design, each with an expanded text area to accommodate multiple abilities and attribute icons.</p>
      </div>
      
      <div style={{ 
        display: 'flex', 
        gap: '10px', 
        marginBottom: '20px',
        borderBottom: '1px solid #eee'
      }}>
        <button 
          onClick={() => setActiveVariant('variant1')}
          style={{
            padding: '8px 16px',
            background: activeVariant === 'variant1' ? '#3498db' : 'transparent',
            color: activeVariant === 'variant1' ? 'white' : '#333',
            border: 'none',
            borderRadius: '4px 4px 0 0',
            cursor: 'pointer',
            fontWeight: activeVariant === 'variant1' ? 'bold' : 'normal'
          }}
        >
          Variant 1: Extended Text Area
        </button>
        <button 
          onClick={() => setActiveVariant('variant2')}
          style={{
            padding: '8px 16px',
            background: activeVariant === 'variant2' ? '#3498db' : 'transparent',
            color: activeVariant === 'variant2' ? 'white' : '#333',
            border: 'none',
            borderRadius: '4px 4px 0 0',
            cursor: 'pointer',
            fontWeight: activeVariant === 'variant2' ? 'bold' : 'normal'
          }}
        >
          Variant 2: Dark Elegant
        </button>
        <button 
          onClick={() => setActiveVariant('variant3')}
          style={{
            padding: '8px 16px',
            background: activeVariant === 'variant3' ? '#3498db' : 'transparent',
            color: activeVariant === 'variant3' ? 'white' : '#333',
            border: 'none',
            borderRadius: '4px 4px 0 0',
            cursor: 'pointer',
            fontWeight: activeVariant === 'variant3' ? 'bold' : 'normal'
          }}
        >
          Variant 3: Tabbed Design
        </button>
        <button 
          onClick={() => setActiveVariant('variant4')}
          style={{
            padding: '8px 16px',
            background: activeVariant === 'variant4' ? '#3498db' : 'transparent',
            color: activeVariant === 'variant4' ? 'white' : '#333',
            border: 'none',
            borderRadius: '4px 4px 0 0',
            cursor: 'pointer',
            fontWeight: activeVariant === 'variant4' ? 'bold' : 'normal'
          }}
        >
          Variant 4: Two Column
        </button>
      </div>
      
      <div style={{ display: 'flex', gap: '30px', alignItems: 'flex-start' }}>
        <div style={{
          display: 'flex',
          justifyContent: 'center',
          padding: '20px',
          background: '#f5f5f5',
          borderRadius: '8px'
        }}>
          {activeVariant === 'variant1' && <Variant1 />}
          {activeVariant === 'variant2' && <Variant2 />}
          {activeVariant === 'variant3' && <Variant3 />}
          {activeVariant === 'variant4' && <Variant4 />}
        </div>
        
        <div style={{ flex: 1 }}>
          <h3>Design Features</h3>
          
          {activeVariant === 'variant1' && (
            <div>
              <p><strong>Extended Text Area Design</strong></p>
              <ul style={{ paddingLeft: '20px' }}>
                <li>Modified version of the original diagonal design</li>
                <li>Larger text area with scrollable content</li>
                <li>Attribute icons for improved visual recognition</li>
                <li>Distinct sections for passive and active abilities</li>
                <li>Cost display for active abilities</li>
                <li>Ability type and subtype information included</li>
                <li>Maintained diagonal visual elements for dynamic feel</li>
              </ul>
            </div>
          )}
          
          {activeVariant === 'variant2' && (
            <div>
              <p><strong>Dark Elegant Design</strong></p>
              <ul style={{ paddingLeft: '20px' }}>
                <li>Sleek, elegant design with colored accent bar</li>
                <li>Clean organization with section headers</li>
                <li>Left-bordered ability cards for strong visual structure</li>
                <li>Diamond-shaped section indicators</li>
                <li>Square attribute displays with icons</li>
                <li>Full card border in faction color</li>
                <li>Ability details clearly separated with strong hierarchy</li>
              </ul>
            </div>
          )}
          
          {activeVariant === 'variant3' && (
            <div>
              <p><strong>Tabbed Interface Design</strong></p>
              <ul style={{ paddingLeft: '20px' }}>
                <li>Interactive tabbed interface for switching between ability types</li>
                <li>Compact attribute display to maximize ability text area</li>
                <li>Rounded top container for modern UI feel</li>
                <li>Maintains diagonal header element from original design</li>
                <li>Colored tabs with active state indicators</li>
                <li>Left-bordered ability cards for clean visual structure</li>
                <li>Clear organization through tab-based content display</li>
              </ul>
            </div>
          )}
          
          {activeVariant === 'variant4' && (
            <div>
              <p><strong>Two Column Design</strong></p>
              <ul style={{ paddingLeft: '20px' }}>
                <li>Splits abilities into two columns for maximum text space</li>
                <li>Horizontal attributes bar to create more space for abilities</li>
                <li>Full width faction name badge</li>
                <li>Column headers to clearly separate passive and active abilities</li>
                <li>Balanced layout with equal space for both ability types</li>
                <li>Colored top bar for faction identity</li>
                <li>Compact but clear presentation of ability details</li>
              </ul>
            </div>
          )}
          
          <div style={{ marginTop: '20px' }}>
            <h3>Notes & Recommendations</h3>
            <p>All designs feature:</p>
            <ul style={{ paddingLeft: '20px' }}>
              <li>Larger text areas to accommodate multiple abilities</li>
              <li>Attribute icons for improved visual communication</li>
              <li>Clear organization of passive and active abilities</li>
              <li>Display of cost, range, type and subtype for active abilities</li>
              <li>Strong use of faction color as a visual element</li>
              <li>Maintained standard TCG card dimensions</li>
              <li>Improved readability through contrast and text hierarchy</li>
            </ul>
            <p style={{ marginTop: '15px' }}>The tabbed design (Variant 3) offers the most space for ability text while the two-column layout (Variant 4) provides the best overview of all abilities at once. The dark elegant design (Variant 2) offers the most sophisticated aesthetic while the extended text area design (Variant 1) stays closest to your original preferred design.</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default HeroCardVariations;