pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
               echo 'Build step sets up deployment env.'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing a new feature.'
            }
        }
        stage('Deploy') {
            steps {
                sh 'git-ftp push -u ${FTP_USERNAME} -p ${FTP_PASSWORD} 160.153.55.233'
                echo 'Deployment completed by Rance Aaron'
            }
        }
    }
}