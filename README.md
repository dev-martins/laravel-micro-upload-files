## Microservice Upload Files

Desenvolvido para upload de todos os tipos de arquivos, porém com finalidade especial para upload de videos e imagens.
O Microservice Upload Files após o upload do video gera cópias com dimensões diferentes, caputra a thumb para cada formato, a duração do video e armazena todas essas informações no banco do dados.
Todo o processo após o upload do video principal é feito com a utilização de filas.